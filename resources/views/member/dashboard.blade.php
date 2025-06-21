@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <h4 class="page-title">Member Dashboard</h4>
            <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}! Here's your monthly summary.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card summary-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-bg-primary me-3">
                        <i class="fa-solid fa-house-user fa-2x text-primary"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Joined Messes</p>
                        <h4 class="fw-bold mb-0">{{ $joinedMessCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-bg-success me-3">
                        <i class="fa-solid fa-utensils fa-2x text-success"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Meals This Month</p>
                        <h4 class="fw-bold mb-0">{{ $totalMealsThisMonth }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-bg-info me-3">
                        <i class="fa-solid fa-dollar-sign fa-2x text-info"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Expenses This Month</p>
                        <h4 class="fw-bold mb-0">৳{{ number_format($totalExpenseThisMonth, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row mt-4">
        <!-- Daily Meal Chart -->
        <div class="col-lg-7 mb-4 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Daily Meals (Last 7 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="meal-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Activity Section -->
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    @if($activities->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="fa-solid fa-bell-slash fa-3x mb-3 text-light"></i>
                            <p>No recent activities to show.</p>
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($activities as $activity)
                                <li class="list-group-item list-group-item-action px-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            @switch($activity->activity_type)
                                                @case('Expense')
                                                    <div class="activity-icon bg-info"><i class="fa-solid fa-cash-register"></i></div>
                                                    <div>
                                                        <p class="mb-0 fw-bold">Expense of ৳{{ $activity->amount }}</p>
                                                        <small class="text-muted">{{ Str::limit($activity->description, 30) }}</small>
                                                    </div>
                                                    @break
                                                @case('Meals Added')
                                                     <div class="activity-icon bg-success"><i class="fa-solid fa-plate-wheat"></i></div>
                                                    <div>
                                                        <p class="mb-0 fw-bold">{{ $activity->meal_count }} Meals Added</p>
                                                        <small class="text-muted">On {{ Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</small>
                                                    </div>
                                                    @break
                                            @endswitch
                                        </div>
                                        <small class="text-muted">{{ Carbon\Carbon::parse($activity->activity_date)->diffForHumans() }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
    .icon-bg-primary, .icon-bg-success, .icon-bg-info {
        padding: 1.25rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-bg-primary { background-color: rgba(74, 144, 226, 0.1); }
    .icon-bg-success { background-color: rgba(40, 167, 69, 0.1); }
    .icon-bg-info { background-color: rgba(23, 162, 184, 0.1); }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
    }
    .activity-icon.bg-success { background-color: #28a745; }
    .activity-icon.bg-info { background-color: #17a2b8; }

    .list-group-item-action {
        transition: background-color 0.2s ease-in-out;
        border-bottom: 1px solid #f0f0f0 !important;
    }
    .list-group-item-action:last-child {
        border-bottom: 0 !important;
    }
    .list-group-item-action:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('meal-chart').getContext('2d');
        const mealChartData = @json($mealChartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(mealChartData).map(date => new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                datasets: [{
                    label: 'Number of Meals',
                    data: Object.values(mealChartData),
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                     x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush 