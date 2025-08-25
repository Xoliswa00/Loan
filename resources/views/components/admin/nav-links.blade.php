<div>
    @can('access-admin')
    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
        🛠️ Admin Dashboard
    </x-nav-link>

    <x-nav-link :href="route('admin.loans.index')" :active="request()->routeIs('admin.loans.*')">
        📄 Loan Approvals
    </x-nav-link>

    <x-nav-link :href="route('admin.repayments.index')" :active="request()->routeIs('admin.repayments.*')">
        💸 Repayments
    </x-nav-link>

    <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')">
        👤 Customers
    </x-nav-link>

    <x-nav-link :href="route('admin.gl.index')" :active="request()->routeIs('admin.gl.*')">
        📊 GL Reports
    </x-nav-link>

    @if(Auth::user()->role === 'owner')
        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
            ⚙️ User Management
        </x-nav-link>
    @endif
@endcan

    <!-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger -->
</div>