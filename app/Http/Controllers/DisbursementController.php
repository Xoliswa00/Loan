<?php
namespace App\Http\Controllers;

use App\Models\LoanDisbursement;
use app\Services\DisbursementService;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class DisbursementController extends Controller
{
protected $disbursementService;

    public function __construct(DisbursementService $disbursementService)
    {
        $this->disbursementService = $disbursementService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all disbursements
        $disbursements = loanDisbursement::with('loan', 'approver')->get();
        return view('disbursements.index', compact('disbursements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Loan $loan)
    {
        // Only allow creation of disbursement if the loan is approved
        if ($loan->status !== 'approved') {
            return redirect()->route('loans.index')->with('error', 'Loan not approved for disbursement.');
        }

        return view('disbursements.create', compact('loan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the disbursement request
        $validatedData = $request->validate([
            'loan_id' => ['required', 'exists:loans,id'],
            'disbursed_amount' => ['required', 'numeric', 'min:0.01'],
            'disbursement_method' => ['required', 'in:bank_transfer,cash,check,other'],
            'disbursement_date' => ['required', 'date'],
            'payment_reference' => ['nullable', 'string'],
            'proof_of_payment' => ['nullable', 'file', 'mimes:jpg,png,pdf'],
        ]);
    
        // Capture the approver's ID (logged-in user)
        $validatedData['approver_id'] = auth()->id();
    
        // Handle file upload for proof of payment
        if ($request->hasFile('proof_of_payment')) {
            // Store the uploaded file and get its path
            $proofOfPaymentPath = $request->file('proof_of_payment')->store('proofs', 'public');
            // Add the file path to the validated data
            $validatedData['proof_of_payment'] = $proofOfPaymentPath;
        }
    
        // Create a new loan disbursement
        $disbursement = LoanDisbursement::create($validatedData);
    
        // Update the loan status to 'disbursed' after disbursement
        $loan = Loan::findOrFail($validatedData['loan_id']);
        $loan->status = 'disbursed';
        $loan->disbursed_at = $validatedData['disbursement_date'];  // Optional: Set disbursement date
        $loan->save();
    
        // Redirect to loan listings with success message
        return redirect()->route('loans.index')->with('success', 'Loan disbursed successfully.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(loanDisbursement $disbursement)
    {
        // Show detailed disbursement information
        return view('disbursements.show', compact('disbursement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(loanDisbursement $disbursement)
    {
        // Only allow editing if the disbursement is not processed
        if ($disbursement->status === 'processed') {
            return redirect()->route('disbursements.index')->with('error', 'Processed disbursements cannot be edited.');
        }

        return view('disbursements.edit', compact('disbursement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, loanDisbursement $disbursement)
    {
        // Validate the update request
        $validatedData = $request->validate([
            'disbursed_amount' => ['required', 'numeric', 'min:0.01'],
            'disbursement_method' => ['required', 'in:bank_transfer,cash,check,other'],
            'disbursement_date' => ['required', 'date'],
        ]);

        // Update disbursement details
        $disbursement->update($validatedData);

        return redirect()->route('disbursements.index')->with('success', 'Disbursement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(loanDisbursement $disbursement)
    {
        // Only allow deletion if the disbursement is not processed
        if ($disbursement->status === 'processed') {
            return redirect()->route('disbursements.index')->with('error', 'Processed disbursements cannot be deleted.');
        }

        // Delete disbursement
        $disbursement->delete();

        return redirect()->route('disbursements.index')->with('success', 'Disbursement deleted successfully.');
    }

    
 public function approve($id)
    {
  $user = auth()->user();

        try {
            $disbursement = $this->disbursementService->approveAndPost($id, $user->id);

  

            return redirect()->back()->with('success', 'Disbursement approved and GL posted.');
        } catch (\Exception $e) {
            Log::error('Disbursement approve error: '.$e->getMessage(), ['id'=>$id]);
            return redirect()->back()->with('error', 'Failed to approve disbursement: '.$e->getMessage());
        }


     





       
    }

    /**
     * Reject a disbursement
     */
    public function reject(Request $request, $id)
    {
        $disbursement = LoanDisbursement::findOrFail($id);

        if ($disbursement->status !== 'waiting_for_approval') {
            return back()->with('error', 'This disbursement cannot be rejected.');
        }

        $disbursement->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->input('rejection_reason', 'No reason provided'),
        ]);

        return back()->with('success', 'Disbursement rejected successfully.');
    }

    /**
     * Release funds
     */
    public function release($id)
    {
        $disbursement = LoanDisbursement::findOrFail($id);

        if ($disbursement->status !== 'approved') {
            return back()->with('error', 'This disbursement cannot be released.');
        }

        $disbursement->update([
            'status' => 'released',
            'released_by' => Auth::id(),
            'released_at' => now(),
        ]);

        // You could also trigger actual fund transfer logic here.

        return back()->with('success', 'Funds released successfully.');
    }

    /**
     * Bulk approve all pending disbursements
     */
    public function approveAll()
    {
        $pending = LoanDisbursement::where('status', 'waiting_for_approval')->get();

        if ($pending->isEmpty()) {
            return back()->with('error', 'No pending disbursements found.');
        }

        foreach ($pending as $disbursement) {
            $disbursement->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
        }

        return back()->with('success', count($pending) . ' disbursement(s) approved successfully.');
    }








}
