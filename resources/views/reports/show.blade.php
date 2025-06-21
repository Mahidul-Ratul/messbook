@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Monthly Report: {{ $mess->name }}</h4>
        <div>
            <form action="{{ route('reports.show', $mess) }}" method="GET" class="d-flex gap-2">
                <a href="{{ route('reports.show', $mess) }}" class="btn btn-primary">Current Month</a>
                <select name="month" class="form-select" onchange="this.form.submit()">
                    <option disabled selected>Previous Months</option>
                    @foreach($previousMonths as $month)
                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($month)->format('F Y') }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Report for {{ $report['month'] }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4 text-center">
                <div class="col-md-3">
                    <div class="stat-card">
                        <h6>Total Meals</h6>
                        <p class="fs-4 fw-bold">{{ number_format($report['total_meals'], 2) }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h6>Total Expenses</h6>
                        <p class="fs-4 fw-bold">{{ number_format($report['total_expenses'], 2) }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h6>Meal Rate</h6>
                        <p class="fs-4 fw-bold">{{ number_format($report['meal_rate'], 2) }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h6>Members</h6>
                        <p class="fs-4 fw-bold">{{ $report['member_count'] }}</p>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Member Name</th>
                        <th class="text-end">Total Meals</th>
                        <th class="text-end">Deposited Amount</th>
                        <th class="text-end">Cost for Meals</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report['members_data'] as $memberData)
                    <tr>
                        <td>{{ $memberData['name'] }}</td>
                        <td class="text-end">{{ $memberData['total_meals'] }}</td>
                        <td class="text-end">{{ number_format($memberData['total_expenses'], 2) }}</td>
                        <td class="text-end">{{ number_format($memberData['cost_for_meals'], 2) }}</td>
                        <td class="text-end @if($memberData['balance'] >= 0) text-success @else text-danger @endif">
                            <strong>{{ number_format($memberData['balance'], 2) }}</strong>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fa-solid fa-box-open fa-2x mb-2"></i>
                            <p>No data available for this month.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title {
    font-weight: 600;
}
.stat-card {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: .5rem;
    border: 1px solid #e0e0e0;
}
</style>
@endpush 