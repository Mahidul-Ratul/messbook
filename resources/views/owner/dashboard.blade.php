@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <h4 class="page-title">Owner Dashboard</h4>
            <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}! Here's an overview of your mess.</p>
        </div>
    </div>

    @php
        $userMess = \App\Models\Mess::where('owner_id', Auth::id())->first();
    @endphp

    @if(!$userMess)
        <!-- Create First Mess Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-center text-white p-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-house-chimney fa-4x mb-3"></i>
                            <h3 class="mb-3">Welcome to MessBook!</h3>
                            <p class="mb-4 fs-5">You're all set up as a Mess Owner. Now it's time to create your first mess and start managing it.</p>
                        </div>
                        <a href="{{ route('owner.messes.create') }}" class="btn btn-light btn-lg px-4 py-2">
                            <i class="fa-solid fa-plus-circle me-2"></i>Create Your First Mess
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card summary-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-bg-primary me-3">
                            <i class="fa-solid fa-users fa-2x text-primary"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Members</p>
                            <h4 class="fw-bold mb-0">{{ $totalMembers }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card summary-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-bg-success me-3">
                            <i class="fa-solid fa-bowl-food fa-2x text-success"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Meals This Month</p>
                            <h4 class="fw-bold mb-0">{{ $totalMealsThisMonth }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card summary-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-bg-warning me-3">
                            <i class="fa-solid fa-money-bill-wave fa-2x text-warning"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Expenses This Month</p>
                            <h4 class="fw-bold mb-0">{{ number_format($totalExpensesThisMonth, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <a href="{{ route('owner.members.requests') }}" class="text-decoration-none">
                    <div class="card summary-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-bg-info me-3">
                                <i class="fa-solid fa-user-plus fa-2x text-info"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Pending Requests</p>
                                <h4 class="fw-bold mb-0">{{ $pendingJoinRequestsCount }}</h4>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">Meals Trend (Last 7 Days)</div>
                    <div class="card-body">
                        <canvas id="mealChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">Expenses Trend (Last 7 Days)</div>
                    <div class="card-body">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity and Pending Requests -->
        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">Recent Activity</div>
                    <div class="card-body">
                        @forelse($activities as $activity)
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0 me-3">
                                    @if($activity instanceof \App\Models\DailyMeal) <i class="fa-solid fa-bowl-food fa-2x text-info"></i>
                                    @elseif($activity instanceof \App\Models\Expense) <i class="fa-solid fa-money-bill-wave fa-2x text-warning"></i>
                                    @elseif($activity instanceof \App\Models\MessJoinRequest) <i class="fa-solid fa-user-check fa-2x text-success"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    @if($activity instanceof \App\Models\DailyMeal)
                                        <strong>{{ $activity->user->name }}</strong> added <strong>{{$activity->total_meal}} meals</strong> to {{ $activity->mess->name }}.
                                    @elseif($activity instanceof \App\Models\Expense)
                                        <strong>{{ $activity->user->name }}</strong> posted an expense of <strong>{{ number_format($activity->amount, 2) }}</strong> for {{ $activity->mess->name }}.
                                    @elseif($activity instanceof \App\Models\MessJoinRequest)
                                        <strong>{{ $activity->user->name }}</strong>'s request to join <strong>{{ $activity->mess->name }}</strong> was approved.
                                    @endif
                                    <small class="d-block text-muted">{{ $activity->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No recent activity.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">Pending Join Requests</div>
                    <div class="card-body">
                        @forelse($pendingRequests as $request)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                 <div>
                                    <h6 class="mb-0">{{ $request->user->name }}</h6>
                                    <small class="text-muted">Wants to join {{ $request->mess->name }}</small>
                                </div>
                                <a href="{{ route('owner.members.requests') }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        @empty
                            <p class="text-muted">No pending requests.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.page-title { font-weight: 600; }
.summary-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 0;
    border-radius: 0.75rem;
}
.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
}
.icon-bg-primary, .icon-bg-success, .icon-bg-warning, .icon-bg-info {
    padding: 1.25rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.icon-bg-primary { background-color: rgba(74, 144, 226, 0.1); }
.icon-bg-success { background-color: rgba(40, 167, 69, 0.1); }
.icon-bg-warning { background-color: rgba(255, 193, 7, 0.1); }
.icon-bg-info { background-color: rgba(23, 162, 184, 0.1); }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($userMess)
    const mealCtx = document.getElementById('mealChart').getContext('2d');
    new Chart(mealCtx, {
        type: 'line',
        data: {
            labels: @json($mealChartData->keys()),
            datasets: [{
                label: 'Total Meals',
                data: @json($mealChartData->values()),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const expenseCtx = document.getElementById('expenseChart').getContext('2d');
    new Chart(expenseCtx, {
        type: 'bar',
        data: {
            labels: @json($expenseChartData->keys()),
            datasets: [{
                label: 'Total Expenses',
                data: @json($expenseChartData->values()),
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
    @endif
});
</script>
@endpush
