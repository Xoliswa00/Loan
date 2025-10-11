<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\Loan;
use App\Models\Loan_fee_rules;
use App\Models\LoanFee;
use App\Models\Customer;


use App\Models\Loans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\RepaymentSchedule;
use App\Models\LoanFeeRules;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;



class LoanApplicationController extends Controller
{
    // Display a list of loan applications
    public function index()
    {
        $applications = LoanApplication::where('user_id', auth()->id())
        ->with('user') // Include user relationship
        ->paginate(10);
        
        return view('applications.index', ['Applications' => $applications]);
    }

public function create()
{
    $user = Auth::user();

      // Fix: use accountDetails() relationship
      $userBankAccounts = $user->accountDetails ?? collect();

      // Ensure it's a collection to avoid errors
      $hasBankAccounts = $userBankAccounts->isNotEmpty();
      
    $createdAt = $user->created_at ?? now()->subYears(1);
    $monthsSince = now()->diffInMonths($createdAt);
    $loyaltyStatus = $monthsSince >= 12 ? 'loyal_returnee' : 'first_timer';

    // Fetch and group applicable rules
    $rules = Loan_fee_rules::where('active', true)
        ->whereIn('applicability', [$loyaltyStatus, 'general'])
        ->orderByRaw("FIELD(applicability, ?, ?)", [$loyaltyStatus, 'general']) // Prioritize specific
        ->get()
        ->groupBy('fee_type')
        ->map(fn($group) => $group->first()); // Pick the specific rule if available

   
    return view('applications.create', [
        'feeRules' => $rules,
        'loyaltyStartDate' => $createdAt,
        'loyaltyStatus' => $loyaltyStatus,
        'userBankAccounts'=>$userBankAccounts,
        'hasBankAccounts'=>$hasBankAccounts
    ]);
}


    // Store a new loan application
  

    // Show a specific loan application
    public function show($id)
    {
        $application = LoanApplication::with('user')->findOrFail($id);
        return view('applications.show', ['application' => $application]);
    }

    // Show the form for editing a loan application
    public function edit($id)
    {
        $application = LoanApplication::findOrFail($id);
        return view('applications.edit', ['application' => $application]);
    }

    // Update a specific loan application
    public function update(Request $request, $id)
    {
        $request->validate([
            'loan_type' => 'required|in:personal,home,business',
            'loan_amount' => 'required|numeric|min:0',
            'purpose' => 'required|string',
            'collateral' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
            'approval_date' => 'nullable|date',
            'arrears' => 'required|boolean',
        ]);

        $loanApplication = LoanApplication::findOrFail($id);
        $loanApplication->loan_type = $request->loan_type;
        $loanApplication->loan_amount = $request->loan_amount;
        $loanApplication->purpose = $request->purpose;
        $loanApplication->collateral = $request->collateral;
        $loanApplication->status = $request->status;
        $loanApplication->approval_date = $request->approval_date;
        $loanApplication->arrears = $request->arrears;

        $loanApplication->save();

        return redirect()->route('applications.index')->with('success', 'Loan application updated successfully.');
    }

    // Delete a specific loan application
    public function destroy($id)
    {
        $application = LoanApplication::findOrFail($id);

        // Delete associated files
        if ($application->credit_score_report) {
            Storage::delete($application->credit_score_report);
        }
        if ($application->bank_statement) {
            Storage::delete($application->bank_statement);
        }
        if ($application->payslips) {
            Storage::delete($application->payslips);
        }

        $application->delete();

        return redirect()->route('applications.index')->with('success', 'Loan application deleted successfully.');
    }



//Store loan Application with all needed cal to make the approval faster 

public function store(Request $request)
{
    $validated = $request->validate([
        'loan_type' => 'required|in:personal,home,business',
        'loan_amount' => 'required|numeric|min:100',
        'months' => 'required|integer|min:1|max:12',
        'purpose' => 'nullable|string',
        'other_purpose' => 'nullable|string',
        'collateral' => 'nullable|string',
        'credit_score_report' => 'nullable|file',
        'bank_statement' => 'required|file',
        'payslips' => 'required|file',
        'terms_conditions' => 'accepted',
    ]);

    $user = Auth::user();
    $loanAmount = $request->loan_amount;
    $months = $request->months;

    // Step 1: Determine Interest Rate based on Loyalty
    $firstLoan = Loan::where('user_id', $user->id)->where('status', 'approved')->orderBy('approved_at')->first();
    $now = now();
    $interestRate = 0.05;

 if ($firstLoan) {
    $approvedAt = Carbon::parse($firstLoan->approved_at);
    $diffMonths = $approvedAt->diffInMonths(now());
    $interestRate = $diffMonths <= 12 ? 0.03 : 0.05;
}

    // Step 2: Calculate Fees
    $interest = $loanAmount * $interestRate;
    $initiationFee = 165 + max(0, ($loanAmount - 1000) * 0.10);
    $initiationFee = min($initiationFee, 1050);
    $serviceFee = 60 * $months;
    $totalRepayment = $loanAmount + $interest + $initiationFee + $serviceFee;
    $monthlyRepayment = round($totalRepayment / $months, 2);

    // Step 3: Store Application
    $application = new LoanApplication();
    $application->user_id = $user->id;
    $application->loan_type = $validated['loan_type'];
    $application->loan_amount = $loanAmount;
    $application->purpose = $validated['purpose'] ?? $validated['other_purpose'] ?? 'N/A';
    $application->collateral = $validated['collateral'] ?? null;
    $application->terms_conditions = true;
    $application->status = 'pending';
    $application->reviewer_id=Auth::user()->id;

    if ($request->hasFile('credit_score_report')) {
        $application->credit_score_report = $request->file('credit_score_report')->store('credit_score_reports');
    }
    if ($request->hasFile('bank_statement')) {
        $application->bank_statement = $request->file('bank_statement')->store('bank_statements');
    }
    if ($request->hasFile('payslips')) {
        $application->payslips = $request->file('payslips')->store('payslips');
    }

    $application->save();

    // Step 4: Save Fee Breakdown
    LoanFee::create([
        'loan_application_id' => $application->id,
        'interest_rate' => $interestRate,
        'interest_amount' => $interest,
        'initiation_fee' => $initiationFee,
        'service_fee' => $serviceFee,
        'total_due' => $totalRepayment,
    ]);

    // Step 5: Generate Repayment Schedule
    $this->generateRepaymentSchedule($application, $monthlyRepayment, $months);

    //Step 6 create AR Customer 
    $existingCustomer = Customer::where('user_id', $user->id)->first();

if (!$existingCustomer) {
    $idNumber = preg_replace('/[^0-9]/', '', $user->ID_Number ?? '000000');
    $prefix = 'CLH';
    $shortId = substr($idNumber, 0, 6);
    $customerCode = $prefix . $user->id . '-' . $shortId;

    Customer::create([
        'user_id' => $user->id,
        'customer_code' => $customerCode,
        'customer_type' => 'individual',
        'payment_terms' => 'monthly',
    ]);
}

    return redirect()->route('loanapplications')->with('success', 'Loan application submitted successfully.');
}

private function generateRepaymentSchedule($loanApplication, $monthlyRepayment, $months)
{
    $user = Auth::user();
    $startDate = now();
    $repayDay = $user->salary_payment_day ?? 1;

    $dueDate = $startDate->copy()->day($repayDay);
    if ($dueDate->isPast()) {
        $dueDate->addMonth();
    }

    for ($i = 0; $i < $months; $i++) {
        RepaymentSchedule::create([
            'loan_id' => $loanApplication->id,
            'user_id' => $user->id,
            'due_date' => $dueDate->copy()->addMonthsNoOverflow($i),
            'emi_amount' => $monthlyRepayment,
            'status' => 'pending',
        ]);
    }
}


    public function adminIndex()
{
    $applications = LoanApplication::with(['user.customer'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    return view('admin.loan_applications.index', compact('applications'));
}


}
