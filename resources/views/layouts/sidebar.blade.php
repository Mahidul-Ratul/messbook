<style>
    .
</style>
@auth
    @role('admin')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-chart-line fa-fw"></i>
                <span class="nav-link-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                <i class="fa-solid fa-users fa-fw"></i>
                <span class="nav-link-text">Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.messes') ? 'active' : '' }}" href="{{ route('admin.messes') }}">
                <i class="fa-solid fa-house-chimney fa-fw"></i>
                <span class="nav-link-text">Messes</span>
            </a>
        </li>
    @endrole

    @role('member')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}" href="{{ route('member.dashboard') }}">
                <i class="fa-solid fa-chart-line fa-fw"></i>
                <span class="nav-link-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('member.messes.join') ? 'active' : '' }}" href="{{ route('member.messes.join') }}">
                <i class="fa-solid fa-person-circle-plus fa-fw"></i>
                <span class="nav-link-text">Join a Mess</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('member.messes.my') ? 'active' : '' }}" href="{{ route('member.messes.my') }}">
                <i class="fa-solid fa-house-chimney-user fa-fw"></i>
                <span class="nav-link-text">My Messes</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('member.meals.*') ? 'active' : '' }}" href="{{ route('member.meals.index') }}">
                <i class="fa-solid fa-bowl-food fa-fw"></i>
                <span class="nav-link-text">My Meals</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('member.expenses.*') ? 'active' : '' }}" href="{{ route('member.expenses.index') }}">
                <i class="fa-solid fa-money-bill-transfer fa-fw"></i>
                <span class="nav-link-text">My Expenses</span>
            </a>
        </li>
    @endrole

    @role('mess_owner')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
                <i class="fa-solid fa-tachograph-digital fa-fw"></i> <span class="nav-link-text">Dashboard</span>
            </a>
        </li>
        
        @php
            $userMess = \App\Models\Mess::where('owner_id', Auth::id())->first();
        @endphp
        
        @if($userMess)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('owner.mess.*') ? 'active' : '' }}" href="{{ route('owner.mess.show') }}">
                    <i class="fa-solid fa-house-chimney fa-fw"></i> <span class="nav-link-text">Mess Details</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('owner.members.*') ? 'active' : '' }}" href="{{ route('owner.members.index') }}">
                    <i class="fa-solid fa-users fa-fw"></i> <span class="nav-link-text">Manage Members</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('owner.members.requests') ? 'active' : '' }}" href="{{ route('owner.members.requests') }}">
                    <i class="fa-solid fa-user-plus fa-fw"></i> <span class="nav-link-text">Join Requests</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('owner.daily_meals.*') ? 'active' : '' }}" href="{{ route('owner.daily_meals.index') }}">
                    <i class="fa-solid fa-bowl-food fa-fw"></i> <span class="nav-link-text">Daily Meals</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('owner.messes.create') ? 'active' : '' }}" href="{{ route('owner.messes.create') }}">
                    <i class="fa-solid fa-plus-circle fa-fw"></i> <span class="nav-link-text">Create Your First Mess</span>
                </a>
            </li>
        @endif
    @endrole

    @role('mess_owner')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('owner.reports.index') ? 'active' : '' }}" href="{{ route('owner.reports.index') }}">
                <i class="fa-solid fa-file-invoice-dollar fa-fw"></i>
                <span class="nav-link-text">Monthly Report</span>
            </a>
        </li>
    @endrole

    @unless(Auth::user()->hasRole('mess_owner'))
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">
            <i class="fa-solid fa-clipboard-list fa-fw"></i>
            <span class="nav-link-text">Reports</span>
        </a>
    </li>
    @endunless
@endauth

