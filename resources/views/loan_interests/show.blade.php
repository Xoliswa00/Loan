<!-- resources/views/loan_interests/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Loan Interest Details</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Loan ID:</strong> {{ $loanInterest->loan->id }}</p>
            <p><strong>Interest Rate (%):</strong> {{ $loanInterest->interest_rate }}</p>
            <p><strong>Start Date:</strong> {{ $loanInterest->start_date }}</p>
            <p><strong>End Date:</strong> {{ $loanInterest->end_date ?? 'N/A' }}</p>
        </div>
    </div>

    <a href="{{ route('loan_interests.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
