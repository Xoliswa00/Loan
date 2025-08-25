<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Loan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('loans.update', $loan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="loan_type" class="block text-gray-700 dark:text-gray-400">Loan Type</label>
                            <select name="loan_type" id="loan_type" class="form-control w-full mt-1">
                                <option value="personal" {{ $loan->loan_type == 'personal' ? 'selected' : '' }}>Personal</option>
                                <option value="business" {{ $loan->loan_type == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="home" {{ $loan->loan_type == 'home' ? 'selected' : '' }}>Home</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="loan_amount" class="block text-gray-700 dark:text-gray-400">Loan Amount</label>
                            <input type="number" name="loan_amount" id="loan_amount" class="form-control w-full mt-1" value="{{ $loan->loan_amount }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="interest_rate" class="block text-gray-700 dark:text-gray-400">Interest Rate (%)</label>
                            <input type="number" name="interest_rate" id="interest_rate" class="form-control w-full mt-1" value="{{ $loan->interest_rate }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="loan_term" class="block text-gray-700 dark:text-gray-400">Loan Term (Months)</label>
                            <input type="number" name="loan_term" id="loan_term" class="form-control w-full mt-1" value="{{ $loan->loan_term }}" required>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4">
                            Update Loan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
