@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">{{ $mess->name }}</h4>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fa-solid fa-house-chimney-window fa-3x text-primary me-4"></i>
                        <div>
                            <h4 class="mb-0">{{ $mess->name }}</h4>
                            <p class="text-muted mb-0">Est. {{ $mess->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                    
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-info-tab" data-bs-toggle="tab" data-bs-target="#nav-info" type="button" role="tab" aria-controls="nav-info" aria-selected="true">
                                <i class="fa-solid fa-circle-info me-1"></i> Mess Info
                            </button>
                            <button class="nav-link" id="nav-members-tab" data-bs-toggle="tab" data-bs-target="#nav-members" type="button" role="tab" aria-controls="nav-members" aria-selected="false">
                                <i class="fa-solid fa-users me-1"></i> Members ({{ $mess->members->count() }})
                            </button>
                            <button class="nav-link" id="nav-reports-tab" data-bs-toggle="tab" data-bs-target="#nav-reports" type="button" role="tab" aria-controls="nav-reports" aria-selected="false">
                                <i class="fa-solid fa-chart-pie me-1"></i> Monthly Reports
                            </button>
                        </div>
                    </nav>
                    <div class="tab-content pt-4" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab">
                            <h5><i class="fa-solid fa-map-location-dot me-2 text-secondary"></i>Address</h5>
                            <p class="text-muted ps-4">{{ $mess->location ?? 'No address provided' }}</p>

                            <h5 class="mt-4"><i class="fa-solid fa-scroll me-2 text-secondary"></i>Mess Rules</h5>
                            <div class="ps-4">
                                {!! nl2br(e($mess->rules)) !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-members" role="tabpanel" aria-labelledby="nav-members-tab">
                            <ul class="list-group list-group-flush">
                                @foreach($mess->members as $member)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $member->avatar ? asset('storage/' . $member->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($member->email))) . '?s=40&d=mp' }}" alt="{{ $member->name }}" class="rounded-circle me-3" width="40" height="40">
                                        <div>
                                            <h6 class="mb-0">{{ $member->name }}</h6>
                                            <small class="text-muted">{{ $member->email }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('profile.show', $member) }}" class="btn btn-outline-primary btn-sm">View Profile</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="nav-reports" role="tabpanel" aria-labelledby="nav-reports-tab">
                            <div class="accordion" id="reportsAccordion">
                                @foreach($reports as $index => $report)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                            <strong>Report for {{ $report['month'] }}</strong>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#reportsAccordion">
                                        <div class="accordion-body">
                                            <div class="row mb-3">
                                                <div class="col-md-4"><strong>Total Meals:</strong> <span class="badge bg-primary">{{ number_format($report['total_meals'], 2) }}</span></div>
                                                <div class="col-md-4"><strong>Total Expenses:</strong> <span class="badge bg-success">{{ number_format($report['total_expenses'], 2) }}</span></div>
                                                <div class="col-md-4"><strong>Meal Rate:</strong> <span class="badge bg-info">{{ number_format($report['meal_rate'], 2) }}</span></div>
                                            </div>
                                            
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Member</th>
                                                        <th class="text-end">Meals</th>
                                                        <th class="text-end">Deposited</th>
                                                        <th class="text-end">Meal Cost</th>
                                                        <th class="text-end">Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($report['members_data'] as $memberData)
                                                    <tr>
                                                        <td>{{ $memberData['name'] }}</td>
                                                        <td class="text-end">{{ $memberData['total_meals'] }}</td>
                                                        <td class="text-end">{{ number_format($memberData['total_expenses'], 2) }}</td>
                                                        <td class="text-end">{{ number_format($memberData['cost_for_meals'], 2) }}</td>
                                                        <td class="text-end @if($memberData['balance'] >= 0) text-success @else text-danger @endif">
                                                            {{ number_format($memberData['balance'], 2) }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong><i class="fa-solid fa-user-shield me-2"></i>Mess Owner</strong>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $mess->owner->avatar ? asset('storage/' . $mess->owner->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($mess->owner->email))) . '?s=100&d=mp' }}" alt="{{ $mess->owner->name }}" class="rounded-circle mb-3" width="100" height="100">
                    <h5 class="card-title">{{ $mess->owner->name }}</h5>
                    <p class="card-text text-muted">{{ $mess->owner->email }}</p>
                    <a href="{{ route('profile.show', $mess->owner) }}" class="btn btn-primary">View Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title {
    font-weight: 600;
}
.breadcrumb-item a {
    text-decoration: none;
}
.nav-tabs .nav-link {
    color: #495057;
}
.nav-tabs .nav-link.active {
    color: #0d6efd;
    border-color: #dee2e6 #dee2e6 #fff;
}
</style>
@endpush 