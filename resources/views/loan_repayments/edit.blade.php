<!-- resources/views/loan_repayments/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Loan Repayment</h1>

    <form action="{{ route('loan_repayments.update', $loanRepayment) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="loan_id">Loan</label>
            <select name="loan_id" id="loan_id" class="form-control" required>
                <option value="">Select a Loan</option>
                @foreach($loans as $loan)
                    <option value="{{ $loan->id }}" {{ $loan->id == $loanRepayment->loan_id ? 'selected' : '' }}>
                        {{ $loan->id }} - {{ $loan->borrower_name }}
                    </option>
                @endforeach
            </select>
            @error('loan_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ $loanRepayment->amount }}" required>
            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="repayment_date">Repayment Date</label>
            <input type="date" name="repayment_date" id="repayment_date" class="form-control" value="{{ $loanRepayment->repayment_date }}" required>
            @error('repayment_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="paid" {{ $loanRepayment->status == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ $loanRepayment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="overdue" {{ $loanRepayment->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-warning mt-3">Update Repayment</button>
    </form>
</div>
@endsection
