<div class="bg-gray-800 text-white h-full w-64 p-4 space-y-4">
    <h2 class="text-xl font-bold">Admin Panel</h2>

    <nav class="space-y-2">
        @can('view-loans')
        <a href="{{ route('admin.loans.index') }}" class="block hover:text-yellow-400">📄 Loan Applications</a>
        @endcan

        @can('view-repayments')
        <a href="{{ route('admin.repayments.index') }}" class="block hover:text-yellow-400">💸 Repayments</a>
        @endcan

        @can('view-customers')
        <a href="{{ route('admin.customers.index') }}" class="block hover:text-yellow-400">👤 Customers</a>
        @endcan

        @can('view-gl')
        <a href="{{ route('admin.gl.index') }}" class="block hover:text-yellow-400">📊 General Ledger</a>
        @endcan

        @can('manage-users')
        <a href="{{ route('admin.users.index') }}" class="block hover:text-yellow-400">⚙️ Users & Roles</a>
        @endcan
    </nav>
</div>
