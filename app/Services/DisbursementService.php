<?php

namespace App\Services;

use App\Models\{
    LoanDisbursement,
    arbatch,
    arbatch_entries,
    Loan,
    Customer,
    gl_accounts,
    glmapping,
    LoanFee
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\GLPostingService;
use Exception;

class DisbursementService
{
    protected $glPosting;

    public function __construct(GLPostingService $glPosting)
    {
        $this->glPosting = $glPosting;
    }

    /**
     * Approve loan disbursement → Create AR Batch + Entries → Post to GL.
     * All actions occur in a single transaction.
     */
    public function approveAndPost(int $disbursementId, int $approverId)
    {
        return DB::transaction(function () use ($disbursementId, $approverId) {
            /** @var LoanDisbursement $disb */
            $disb = LoanDisbursement::lockForUpdate()->findOrFail($disbursementId);

            if ($disb->status !== 'waiting_for_approval') {
                throw new Exception("Cannot approve disbursement in state: {$disb->status}");
            }

            // 1️⃣ Approve disbursement
            $disb->update([
                'status' => 'approved',
                'payment_realiser_id' => $approverId,
                'disbursement_date' => now(),
            ]);

            $loan = $disb->loan;
            $customer = $loan->user->customer;

            // 2️⃣ Create AR Batch
            $ref = 'ARB-' . now()->format('YmdHis') . '-' . $disb->id;
$arBatch = new arbatch();
$arBatch->reference      = $ref;
$arBatch->customer_id    = $customer->id;
$arBatch->source_type    = LoanDisbursement::class;
$arBatch->source_id      = $disb->id;
$arBatch->total_amount   = $disb->disbursed_amount;
$arBatch->status         = 'approved';
$arBatch->created_by     = $approverId;
$arBatch->approved_by    = $approverId;
$arBatch->approved_at    = now();
$arBatch->save();

            // 3️⃣ Determine GL Accounts using dynamic mapping
            $branchId = $loan->branch_id ?? 1;
            $locationCode = $loan->location_code ?? '000';

            $loanReceivableGl = $this->getGlAccountFor('loan_disbursement_dr', $branchId, $locationCode);
            $bankGl = $this->getGlAccountFor('loan_disbursement_cr', $branchId, $locationCode);

            if (!$loanReceivableGl || !$bankGl) {
                throw new Exception('Missing GL account mapping for loan receivable or bank.');
            }

            // 4️⃣ Create AR Entries
            arbatch_entries::insert([
                [
                    'arbatch_id' => $arBatch->id,
                    'gl_account_id' => $loanReceivableGl->id,
                    'entry_type' => 'debit',
                    'amount' => $disb->disbursed_amount,
                    'description' => "Loan disbursement #{$disb->id} principal",
                    'loan_id' => $loan->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'arbatch_id' => $arBatch->id,
                    'gl_account_id' => $bankGl->id,
                    'entry_type' => 'credit',
                    'amount' => $disb->disbursed_amount,
                    'description' => "Bank paid for loan disbursement #{$disb->id}",
                    'loan_id' => $loan->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // 5️⃣ Post AR batch to GL (creates GL Batch + entries)
            $glBatch = $this->glPosting->postArBatch($arBatch, $approverId);
            $arBatch->update(['posted_to_gl' => true, 'status' => 'posted']);

            // 6️⃣ Update loan & customer balances
            $totalDue = DB::table('loan_fees')
    ->where('loan_application_id', $loan->loan_application_id)
    ->value('total_due');

$customer->increment('current_balance', $totalDue ?? 0);

            $loan->update([
                'status' => 'disbursed',
                'disbursed_date' => now(),
                'remaining_balance' => ($loan->remaining_balance ?? 0) + $totalDue,
                'next_payment_date' => $loan->next_payment_date ?? now()->addMonth()->startOfDay(),
            ]);





            // 7️⃣ Mark disbursement as released
            $disb->update([
                'status' => 'approved',
                'released_at' => now(),
            ]);

            Log::info("Loan disbursement #{$disb->id} approved and posted by user {$approverId}");

            return $disb->fresh(['loan', 'loan.user', 'loan.user.customer']);
        });
    }

    /**
     * Resolve GL account dynamically from gl_mappings + gl_accounts
     */
    protected function getGlAccountFor(string $key, $branchId = null, $locationCode = null)
    {
        $mapping = glmapping::where('key', $key)->where('is_active', 1)->first();

        if (!$mapping) {
            Log::warning("⚠️ GL mapping not found for key: {$key}");
            return null;
        }

        $baseAccountCode = $mapping->account_code;

        $query = gl_accounts::whereHas('chartOfAccount', function ($q) use ($baseAccountCode) {
            $q->where('account_code', $baseAccountCode);
        });

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($locationCode) {
            $query->where('location_code', $locationCode);
        }

        $glAccount = $query->first()
            ?? gl_accounts::whereHas('chartOfAccount', function ($q) use ($baseAccountCode) {
                $q->where('account_code', $baseAccountCode);
            })->where('branch_id', 1)->first();

        if (!$glAccount) {
            Log::error("❌ No GL account found for mapping: {$key} (base: {$baseAccountCode})");
        }

        return $glAccount;
    }
}
