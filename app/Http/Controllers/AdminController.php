<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Customer;
use App\Models\RepaymentSchedule;
use App\Models\LoanApplication;
use App\Models\LoanDisbursement;
use App\Models\Loan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    return view('admin.dashboard', [
            'pendingLoansCount' => LoanApplication::where('status', 'pending')->count(),
            'customerCount' => Customer::count(),
            'todaysRepayments' => RepaymentSchedule::whereMonth('due_date', now()->month)->sum('emi_amount'),
            'totalLoansDisbursed' => LoanDisbursement::where('status', 'waiting_for_approval')->count(),
        ]);
    }

    public function Loans()
    {

          $pendingApplications = LoanApplication::with('user')
            ->where('status', 'pending')
           // ->latest()
            ->paginate(15);

        return view('admin.loan_applications.index', compact('pendingApplications'));
    

    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
  public function show($id)
{
    $application = LoanApplication::findOrFail($id);

    $previousLoans = LoanApplication::where('user_id', $application->user_id)
        ->where('id', '!=', $application->id)
        ->get();

    return view('admin.loan_applications.show', compact('application', 'previousLoans'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }

    //loan disbursement waiting for approval

    public function Disbursement()
    {
        $disbursements = LoanDisbursement::where('Status', 'waiting_for_approval')->get();
        return view('admin.loans.payments', compact('disbursements'));
      
    
    }
}
