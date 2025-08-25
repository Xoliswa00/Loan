<!-- resources/views/loan_interests/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Loan Interest</h1>

    <form action="{{ route('loan_interests.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="loan_id">Loan</label>
            <select name="loan_id" id="loan_id" class="form-control" required>
                <option value="">Select a Loan</option>
                @foreach($loans as $loan)
                    <option value="{{ $loan->id }}">{{ $loan->id }} - {{ $loan->borrower_name }}</option>
                @endforeach
            </select>
            @error('loan_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="interest_rate">Interest Rate (%)</label>
            <input type="number" name="interest_rate" id="interest_rate" class="form-control" step="0.01" required>
            @error('interest_rate') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
            @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control">
            @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-success mt-3">Create Loan Interest</button>
    </form>
</div>
@endsection
