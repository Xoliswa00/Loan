<!-- resources/views/loan_repayments/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Loan Repayment Details</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Loan ID:</strong> {{ $loanRepayment->loan->id }}</p>
            <p><strong>Amount:</strong> {{ number_format($loanRepayment->amount, 2) }}</p>
            <p><strong>Repayment Date:</strong> {{ $loanRepayment->repayment_date }}</p>
            <p><strong>Status:</strong> {{ ucfirst($loanRepayment->status) }}</p>
        </div>
    </div>

    <a href="{{ route('loan_repayments.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
