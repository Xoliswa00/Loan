<?php

namespace App\Http\Controllers;

use App\Models\RepaymentSchedule;
use App\Models\Loan;
use Illuminate\Http\Request;

class RepaymentScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repaymentSchedules = RepaymentSchedule::with('loan')->get();
        return view('repayment_schedules.index', compact('repaymentSchedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $loans = Loan::all();
        return view('repayment_schedules.create', compact('loans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data with appropriate rules
        $validatedData = $request->validate([
            'loan_id' => ['required', 'exists:loans,id'],
            'due_date' => ['required', 'date'],
            'emi_amount' => ['required', 'numeric', 'min:0.01'], // Ensure consistency with migration field name
            'status' => ['required', 'in:pending,paid,overdue'],
        ]);

        // Create a new repayment schedule
        RepaymentSchedule::create($validatedData);

        // Redirect to the index with a success message
        return redirect()->route('repayment_schedules.index')->with('success', 'Repayment schedule created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RepaymentSchedule $repaymentSchedule)
    {
        // Ensure the loan relationship is loaded
        $repaymentSchedule->load('loan');
        return view('repayment_schedules.show', compact('repaymentSchedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RepaymentSchedule $repaymentSchedule)
    {
        $loans = Loan::all();
        return view('repayment_schedules.edit', compact('repaymentSchedule', 'loans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RepaymentSchedule $repaymentSchedule)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'loan_id' => ['required', 'exists:loans,id'],
            'due_date' => ['required', 'date'],
            'emi_amount' => ['required', 'numeric', 'min:0.01'], // Ensure consistency with migration field name
            'status' => ['required', 'in:pending,paid,overdue'],
        ]);

        // Update the repayment schedule with validated data
        $repaymentSchedule->update($validatedData);

        // Redirect to the index with a success message
        return redirect()->route('repayment_schedules.index')->with('success', 'Repayment schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RepaymentSchedule $repaymentSchedule)
    {
        // Delete the repayment schedule
        $repaymentSchedule->delete();

        // Redirect to the index with a success message
        return redirect()->route('repayment_schedules.index')->with('success', 'Repayment schedule deleted successfully.');
    }
}
