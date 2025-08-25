@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Repayment Schedule Details</h1>

        <div class="card">
            <div class="card-body">
                <p><strong>Loan:</strong> {{ $repaymentSchedule->loan->name }}</p>
                <p><strong>Due Date:</strong> {{ $repaymentSchedule->due_date->format('Y-m-d') }}</p>
                <p><strong>Amount:</strong> {{ number_format($repaymentSchedule->amount, 2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($repaymentSchedule->status) }}</p>
            </div>
        </div>

        <a href="{{ route('repayment_schedules.index') }}" class="btn btn-primary mt-3">Back to List</a>
    </div>
@endsection
