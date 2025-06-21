@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light-primary text-dark-primary shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h2 class="card-title fw-bold">{{ $mess->name }}</h2>
                            <p class="card-text text-muted mb-2">
                                <i class="fa-solid fa-location-dot me-2"></i>{{ $mess->location }}
                            </p>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($mess->owner->name) }}&background=random" alt="{{ $mess->owner->name }}" class="rounded-circle me-2" width="24">
                                <span class="small">Owned by {{ $mess->owner->name }}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="text-muted small mb-0">Created At</p>
                            <p class="fw-bold">{{ $mess->created_at->format('d M, Y') }}</p>
                        </div>
                    </div>
                     @if($mess->description)
                    <hr>
                    <p class="mb-0">{{ $mess->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card summary-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-bg-primary me-3">
                        <i class="fa-solid fa-users fa-2x text-primary"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Total Members</p>
                        <h4 class="fw-bold mb-0">{{ $report['member_count'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                     <div class="icon-bg-success me-3">
                        <i class="fa-solid fa-chart-line fa-2x text-success"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Meal Rate</p>
                        <h4 class="fw-bold mb-0">{{ number_format($report['meal_rate'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-bg-info me-3">
                        <i class="fa-solid fa-bowl-food fa-2x text-info"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Total Meals This Month</p>
                        <h4 class="fw-bold mb-0">{{ $report['total_meals'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Records Chart -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Past 6 Months Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthly-chart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Records Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Detailed Monthly Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Total Meals</th>
                                    <th>Total Expenses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyData as $data)
                                <tr>
                                    <td>{{ $data['month'] }}</td>
                                    <td>{{ $data['total_meals'] }}</td>
                                    <td>{{ number_format($data['total_expenses'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-light-primary {
        background-color: #eef2f9 !important;
    }
    .text-dark-primary {
        color: #001f3f !important;
    }
    .summary-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: 0;
        border-radius: 0.5rem;
    }
    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
    }
    .icon-bg-primary, .icon-bg-success, .icon-bg-info {
        padding: 1rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-bg-primary { background-color: rgba(74, 144, 226, 0.1); }
    .icon-bg-success { background-color: rgba(40, 167, 69, 0.1); }
    .icon-bg-info { background-color: rgba(23, 162, 184, 0.1); }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('monthly-chart').getContext('2d');
        const monthlyData = @json($monthlyData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => d.month).reverse(),
                datasets: [{
                    label: 'Total Meals',
                    data: monthlyData.map(d => d.total_meals).reverse(),
                    backgroundColor: 'rgba(74, 144, 226, 0.7)',
                    borderColor: 'rgba(74, 144, 226, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }, {
                    label: 'Total Expenses',
                    data: monthlyData.map(d => d.total_expenses).reverse(),
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    });
</script>
@endpush 