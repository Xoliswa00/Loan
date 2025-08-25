@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Repayment Schedule</h1>

        <form action="{{ route('repayment_schedules.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="loan_id" class="form-label">Loan</label>
                <select name="loan_id" id="loan_id" class="form-control">
                    @foreach ($loans as $loan)
                        <option value="{{ $loan->id }}">{{ $loan->name }}</option>
                    @endforeach
                </select>
                @error('loan_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date') }}">
                @error('due_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" step="0.01">
                @error('amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Create Repayment Schedule</button>
        </form>
    </div>
@endsection
