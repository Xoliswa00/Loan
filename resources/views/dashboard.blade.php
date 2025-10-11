<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark-200 leading-tight">
            {{ __('Financial Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold mb-2">Welcome Back, {{ Auth::user()->name }}!</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Here's a snapshot of your financial activity and account overview.</p>

                    <!-- Summary Cards Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-md">
                            <h4 class="text-md font-semibold text-indigo-600 dark:text-indigo-300">Current Loan</h4>
                            <p class="text-2xl font-bold mt-2">{{ Auth::user()->customer->current_balance ?? 0, 2 }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Outstanding Balance</p>
                        </div>
                        @php
    $loan = Auth::user()->loans()->where('status', 'approved')->latest()->first();
    $nextPayment = $loan ? $loan->repaymentSchedules()->where('status', 'pending')->orderBy('due_date')->first() : null;
@endphp



                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-md">
                            <h4 class="text-md font-semibold text-green-600 dark:text-green-300">Account Balance</h4>
                            <p class="text-2xl font-bold mt-2">    R{{ number_format($nextPayment->emi_amount ?? 0, 2) }}
</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Available Funds</p>
                        </div>

                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-md">
                            <h4 class="text-md font-semibold text-yellow-600 dark:text-yellow-300">Next Payment</h4>
                            <p class="text-2xl font-bold mt-2">{{ $nextPayment ? $nextPayment->due_date->format('d M Y') : 'N/A' }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Scheduled Date</p>
                        </div>

                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-md">
                            <h4 class="text-md font-semibold text-red-600 dark:text-red-300">Loan Status</h4>
                            <p class="text-2xl font-bold mt-2">Active</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Monthly payments ongoing</p>
                        </div>
                    </div>

                    <!-- Trends and Activity Chart Placeholder -->
                    <div class="mt-10">
                        <h4 class="text-md font-semibold mb-2">Financial Trends</h4>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg h-60 flex items-center justify-center text-gray-500 dark:text-gray-400">
                            <span>Chart View Placeholder (e.g., payment history, balances)</span>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
                        <a href="#" class="bg-indigo-100 dark:bg-indigo-900 p-4 rounded-lg shadow hover:shadow-md transition">
                            <h4 class="font-semibold text-indigo-700 dark:text-indigo-200">Profile</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-300">Update personal details</p>
                        </a>
                        <a href="#" class="bg-green-100 dark:bg-green-900 p-4 rounded-lg shadow hover:shadow-md transition">
                            <h4 class="font-semibold text-green-700 dark:text-green-200">Messages</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-300">View communications</p>
                        </a>
                        <a href="#" class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-lg shadow hover:shadow-md transition">
                            <h4 class="font-semibold text-yellow-700 dark:text-yellow-200">Statements</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-300">Download account history</p>
                        </a>
                        <a href="#" class="bg-red-100 dark:bg-red-900 p-4 rounded-lg shadow hover:shadow-md transition">
                            <h4 class="font-semibold text-red-700 dark:text-red-200">Support</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-300">Get help or open a ticket</p>
                        </a>
                    </div>

                    <!-- Tips / Updates Section -->
                    <div class="mt-8 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <h5 class="text-md font-medium text-gray-700 dark:text-gray-100">Pro Tip</h5>
                        <p class="text-xs text-gray-600 dark:text-gray-300">Set up auto-debit to avoid late fees and improve your credit profile.</p>
                    </div>

                    <!-- Chat Button -->
                    <div class="mt-12 text-center">
                        <button class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                            <span class="material-symbols-rounded mr-2">chat</span>
                            Live Chat with Support
                        </button>
                    </div>

                    <!-- Footer -->
                    <footer class="mt-12 text-center text-xs text-gray-500 dark:text-gray-400">
                        &copy; {{ date('Y') }} Liger Management. All rights reserved.
                    </footer>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
