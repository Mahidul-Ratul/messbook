@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <h4 class="page-title">Admin Dashboard</h4>
            <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}! Here's what's happening.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card summary-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-bg-primary me-3">
                        <i class="fa-solid fa-users fa-2x text-primary"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Total Users</p>
                        <h4 class="fw-bold mb-0">{{ $userCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card summary-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-bg-success me-3">
                        <i class="fa-solid fa-house-user fa-2x text-success"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Total Messes</p>
                        <h4 class="fw-bold mb-0">{{ $messCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card summary-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-bg-info me-3">
                        <i class="fa-solid fa-user-shield fa-2x text-info"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Mess Owners</p>
                        <h4 class="fw-bold mb-0">{{ $ownerCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card summary-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                     <div class="icon-bg-warning me-3">
                        <i class="fa-solid fa-user-check fa-2x text-warning"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Mess Members</p>
                        <h4 class="fw-bold mb-0">{{ $memberCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row mt-2">
        <!-- Activity Section -->
        <div class="col-lg-7 mb-4 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    @if($activities->isEmpty())
                        <div class="text-center text-muted py-4">
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
                                                @case('New Mess')
                                                    <div class="activity-icon bg-success"><i class="fa-solid fa-house-chimney"></i></div>
                                                    <div>
                                                        <p class="mb-0 fw-bold">{{ Str::limit($activity->name, 25) }}</p>
                                                        <small class="text-muted">New mess created by {{ $activity->owner->name }}</small>
                                                    </div>
                                                    @break
                                                @case('Join Request')
                                                     <div class="activity-icon bg-info"><i class="fa-solid fa-user-plus"></i></div>
                                                    <div>
                                                        <p class="mb-0 fw-bold">{{ $activity->user->name }}</p>
                                                        <small class="text-muted">Requested to join {{ Str::limit($activity->mess->name, 20) }}</small>
                                                    </div>
                                                    @break
                                                @case('New User')
                                                     <div class="activity-icon bg-primary"><i class="fa-solid fa-user"></i></div>
                                                    <div>
                                                        <p class="mb-0 fw-bold">{{ $activity->name }}</p>
                                                        <small class="text-muted">Registered as a new user</small>
                                                    </div>
                                                    @break
                                            @endswitch
                                        </div>
                                        <small class="text-muted">{{ $activity->activity_date->diffForHumans() }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Charts Column -->
        <div class="col-lg-5">
            <!-- User Growth Chart -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">New User Registrations</h5>
                </div>
                <div class="card-body">
                    <canvas id="user-chart" style="max-height: 200px;"></canvas>
                </div>
            </div>
            <!-- Role Distribution Chart -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">User Role Distribution</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="role-chart" style="max-height: 200px;"></canvas>
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
    .icon-bg-primary, .icon-bg-success, .icon-bg-info, .icon-bg-warning {
        padding: 1.25rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-bg-primary { background-color: rgba(74, 144, 226, 0.1); }
    .icon-bg-success { background-color: rgba(40, 167, 69, 0.1); }
    .icon-bg-info { background-color: rgba(23, 162, 184, 0.1); }
    .icon-bg-warning { background-color: rgba(255, 193, 7, 0.1); }
    
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
    .activity-icon.bg-primary { background-color: #4a90e2; }
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
        // User Growth Line Chart
        const userCtx = document.getElementById('user-chart').getContext('2d');
        const userChartData = @json($userChartData);
        new Chart(userCtx, {
            type: 'line',
            data: {
                labels: Object.keys(userChartData).map(date => new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                datasets: [{
                    label: 'New Users',
                    data: Object.values(userChartData),
                    backgroundColor: 'rgba(74, 144, 226, 0.1)',
                    borderColor: '#4a90e2',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4a90e2',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // Role Distribution Doughnut Chart
        const roleCtx = document.getElementById('role-chart').getContext('2d');
        const roleDistributionData = @json($roleDistributionData);
        new Chart(roleCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(roleDistributionData),
                datasets: [{
                    label: 'User Roles',
                    data: Object.values(roleDistributionData),
                    backgroundColor: [
                        '#4a90e2', // Admin - Blue
                        '#17a2b8', // Owner - Teal/Info
                        '#ffc107', // Member - Yellow/Warning
                    ],
                    borderColor: '#fff',
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
