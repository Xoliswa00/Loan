<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\LoanApplication;
use App\Models\Loan_fee_rules;
use Illuminate\Http\Request;
use app\Models\AccountDetail;
use app\Models\RepaymentSchedule;
use App\Models\LoanDisbursement;
use app\models\Disbursement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of loans.
     */
    public function index()
    {
        $user = Auth::user();

        // Retrieve all loans with related user and loan application details
        $loans = Loan::where('user_id', $user->id)->latest()
            ->paginate(15);
        $loansapp = LoanApplication::where('user_id', $user->id)->latest()
            ->paginate(15);
                        $disbursements = LoanDisbursement::where('status', 'waiting_for_approval')->get();



      
   
        return view('loans.index', compact('loans','loansapp','disbursements'));
    }

    /**
     * Show the form for creating a new loan.
     */
public function create()
{
    $user = Auth::user();

   

    // Get user's first approved loan to determine loyalty status
    $firstApprovedLoan = Loan::where('user_id', $user->id)
        ->where('status', 'approved')
        ->orderBy('approved_at')
        ->first();

    $loyaltyStartDate = $firstApprovedLoan?->approved_at;

    // Load all dynamic fee rules from the database
    $feeRules = Loan_fee_rules::all()->keyBy('key');
    $userBankAccounts = AccountDetail::where('user_id', $user->id);
    $hasBankAccounts = $userBankAccounts->isNotEmpty();
    return view('applications.create', [
        'loyaltyStartDate' => $loyaltyStartDate,
        'feeRules' => $feeRules,
        'userBankAccounts'=>$userBankAccounts
    ]);
}

    /**
     * Store a newly created loan in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_application_id' => 'required|exists:loan_applications,id',
            'user_id' => 'required|exists:users,id',
            'loan_type' => 'required|in:personal,home,business',
            'loan_amount' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'loan_term' => 'required|integer|min:1',
            'collateral' => 'nullable|string',
            'approved_amount' => 'required|numeric|min:0',
        ]);

        $loan = Loan::create([
            'loan_application_id' => $request->loan_application_id,
            'user_id' => $request->user_id,
            'loan_type' => $request->loan_type,
            'loan_amount' => $request->loan_amount,
            'interest_rate' => $request->interest_rate,
            'loan_term' => $request->loan_term,
            'collateral' => $request->collateral,
            'approved_amount' => $request->approved_amount,
            'remaining_balance' => $request->approved_amount,
            'status' => 'pending',
            'admin_id' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('loans.index')->with('success', 'Loan created successfully.');
    }

    /**
     * Display the specified loan.
     */
    public function show(Loan $loan)
    {
        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified loan.
     */
    public function edit(Loan $loan)
    {
        $loanApplications = LoanApplication::where('status', 'approved')->get();
        $users = User::all();

        return view('loans.edit', compact('loan', 'loanApplications', 'users'));
    }

    /**
     * Update the specified loan in the database.
     */
    public function update(Request $request, LoanApplication $loan)
    {
        $request->validate([
            'loan_application_id' => 'required|exists:loan_applications,id',
            'user_id' => 'required|exists:users,id',
            'loan_type' => 'required|in:personal,home,business',
            'loan_amount' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'loan_term' => 'required|integer|min:1',
            'collateral' => 'nullable|string',
            'approved_amount' => 'required|numeric|min:0',
        ]);

        $loan->update([
            'loan_application_id' => $request->loan_application_id,
            'user_id' => $request->user_id,
            'loan_type' => $request->loan_type,
            'loan_amount' => $request->loan_amount,
            'interest_rate' => $request->interest_rate,
            'loan_term' => $request->loan_term,
            'collateral' => $request->collateral,
            'approved_amount' => $request->approved_amount,
            'remaining_balance' => $request->approved_amount,
        ]);

        return redirect()->route('loans.index')->with('success', 'Loan updated successfully.');
    }

    /**
     * Remove the specified loan from the database.
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();

        return redirect()->route('loans.index')->with('success', 'Loan deleted successfully.');
    }

    /**
     * Approve a loan.
     */
    public function approve(Request $request,$id)
    {
        $request->validate([
            'approval_comments' => 'nullable|string',
        ]);

      

          $loanApplication = LoanApplication::findOrFail($id);

        $loanApplication->status ='approved';
        $loanApplication->approval_date = Now();
        $loanApplication->reason = $request->approval_comments;
         $loanApplication->reviewer_id=auth()->user()->id;

        $loanApplication->save();
        $paymentSchedule = DB::table('repayment_schedules')
            ->where('loan_id', $loanApplication->id)
            ->where('status', 'pending')
            ->orderBy('due_date', 'asc')
            ->first();
        // Create the actual loan record
        $loan = Loan::create([
            'loan_application_id' => $id,
            'user_id' => $loanApplication->user_id,
            'loan_type' => $loanApplication->loan_type,
            'loan_amount' => $loanApplication->loan_amount,
            'interest_rate' => 5.0, // This could be dynamic based on your rules
            'loan_term' => 12, // This could also be dynamic    
            'collateral' => $loanApplication->collateral,
            'approved_amount' => $loanApplication->loan_amount,
            'remaining_balance' => $loanApplication->loan_amount,
            'status' => 'approved',
            'approver_id' => auth()->user()->id,
            'processed_at' => now(),
            'approval_comments'=>$request->approval_comments,
            'approved_at'=>Now(),
            'next_payment_date'=> $paymentSchedule->due_date,
            'installment_frequency'=>2,

        ]);
// 3. Update customer account (assuming you have a Customer model)
        $customer = $loanApplication->user->customer;


        // 4. Create disbursement (waiting for approval)
      $payment=  LoanDisbursement::create([
            'loan_id'     => $loan->id,
            'disbursed_amount'      => $loanApplication->loan_amount,
            'status'      => 'waiting_for_approval',

            'payment_reference'=>$customer->customer_code,
            'approver_id'  => Auth::id(),
            'created_at'  => now(),
        ]);
              

            
         



















        return redirect()->route('admin.dashboard')->with('success', 'Loan approved successfully.');
    }

    /**
     * Reject a loan.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_comments' => 'nullable|string',
        ]);

          $loanApplication = LoanApplication::findOrFail($id);

        $loanApplication->status ='rejected';
        $loanApplication->approval_date = Now();
        $loanApplication->reason = $request->rejection_comments;
         $loanApplication->reviewer_id=auth()->user()->id;

        $loanApplication->save();

        return redirect()->route('Admin.Loans')->with('success', 'Loan rejected successfully.');
    }

    /**
     * Disburse a loan.
     */
    public function disburse(Loan $loan)
    {
        $loan->update([
            'status' => 'disbursed',
            'disbursed_date' => now(),
        ]);

        return redirect()->route('loans.index')->with('success', 'Loan disbursed successfully.');
    }

    /**
     * Update payment details for a loan.
     */
    public function updatePayment(Loan $loan, Request $request)
    {
        $request->validate([
            'remaining_balance' => 'required|numeric|min:0',
            'next_payment_date' => 'nullable|date',
        ]);

        $loan->update([
            'remaining_balance' => $request->remaining_balance,
            'next_payment_date' => $request->next_payment_date,
        ]);

        return redirect()->route('loans.index')->with('success', 'Loan payment details updated successfully.');
    }





}
