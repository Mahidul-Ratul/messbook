@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Monthly Report</h4>
        <form method="GET" action="{{ route('owner.reports.index') }}" class="d-flex gap-2 align-items-center">
            <input type="month" id="month" name="month" class="form-control" value="{{ $selectedMonth }}" required>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-search me-1"></i> Generate
            </button>
        </form>
    </div>

    @if($report)
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Report for {{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4 text-center">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h6>Total Members</h6>
                            <p class="fs-4 fw-bold">{{ count($report['memberReports']) }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h6>Total Meals</h6>
                            <p class="fs-4 fw-bold">{{ $report['totalMeals'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h6>Total Expenses</h6>
                            <p class="fs-4 fw-bold">{{ number_format($report['totalExpenses'], 2) }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h6>Meal Rate</h6>
                            <p class="fs-4 fw-bold">{{ number_format($report['mealRate'], 2) }}</p>
                        </div>
                    </div>
                </div>

                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Member</th>
                            <th class="text-center">Meals</th>
                            <th class="text-end">Expenses</th>
                            <th class="text-end">Bill</th>
                            <th class="text-end">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['memberReports'] as $memberReport)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $memberReport['member']->avatar ? asset('storage/' . $memberReport['member']->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($memberReport['member']->email))) . '?s=40&d=mp' }}" alt="" width="32" height="32" class="rounded-circle me-2">
                                    <span>{{ $memberReport['member']->name }}</span>
                                </div>
                            </td>
                            <td class="text-center">{{ $memberReport['meals'] }}</td>
                            <td class="text-end">{{ number_format($memberReport['expenses'], 2) }}</td>
                            <td class="text-end">{{ number_format($memberReport['bill'], 2) }}</td>
                            <td class="text-end @if($memberReport['balance'] >= 0) text-success @else text-danger @endif">
                                <strong>{{ number_format($memberReport['balance'], 2) }}</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                 <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                <h4 class="card-title fw-bold mb-3">No Report Generated</h4>
                <p class="card-text text-muted mb-4">Select a month above to generate a detailed monthly report.</p>
            </div>
        </div>
    @endif
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
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.table th {
    white-space: nowrap;
}
</style>
@endpush
