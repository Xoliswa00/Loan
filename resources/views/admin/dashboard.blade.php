<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold mb-2">Welcome Back, {{ Auth::user()->name }}!</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Admin overview panel with core system metrics and actions.</p>

                    <!-- Summary Cards Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-md">
                            <h4 class="text-md font-semibold text-indigo-600 dark:text-indigo-300">Pending Loans</h4>
                            <p class="text-2xl font-bold mt-2">{{ $pendingLoansCount ?? '0' }}</p>


                             <a href="{{ route('admin.loans') }}" class="p-4 rounded-lg shadow hover:shadow-md transition">
                            <h4 class="font-semibold text-indigo-700 dark:text-indigo-200">Loan Applications</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Applications to Review</p>
                        </a>
                        </div>
                           <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-md">
                            <h4 class="text-md font-semibold text-indigo-600 dark:text-indigo-300">Pending Payments</h4>
                            <p class="text-2xl font-bold mt-2">{{ $totalLoansDisbursed ?? '0' }}</p>


                             <a href="{{ route('Admin.Disbursement') }}" class="p-4 rounded-lg shadow hover:shadow-md transition">
                            <h4 class="font-semibold text-indigo-700 dark:text-indigo-200">Loan Applications</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Applications to Review</p>
                        </a>
                        </div>

                  

                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-md">
                            <h4 class="text-md font-semibold text-yellow-600 dark:text-yellow-300">Repayments this Month</h4>
                            <p class="text-2xl font-bold mt-2">{{ number_format($todaysRepayments ?? 0, 2) }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Expected this month</p>
                        </div>

                       
                    </div>

                    <!-- Navigation Links -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                        <a href="{{ route('loan.index') }}" class="bg-indigo-100 dark:bg-indigo-900 p-4 rounded-lg shadow hover:shadow-md transition">
                            <h4 class="font-semibold text-indigo-700 dark:text-indigo-200">Loan Applications</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-300">Review, approve, or reject</p>
                        </a>

                
                   
                    </div>

                    <!-- Pro Tip -->
                    <div class="mt-10 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <h5 class="text-md font-medium text-gray-700 dark:text-gray-100">Admin Tip</h5>
                        <p class="text-xs text-gray-600 dark:text-gray-300">Review loan approvals daily and monitor overdue accounts for quick intervention.</p>
                    </div>

                    <!-- Footer -->
                    <footer class="mt-12 text-center text-xs text-gray-500 dark:text-gray-400">
                        &copy; {{ date('Y') }} Liger Admin Panel. All rights reserved.
                    </footer>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
