@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">My Expenses</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
            <i class="fa-solid fa-plus me-1"></i> Add New Expense
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm summary-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-wallet me-2"></i>Total Spent</h5>
                    <p class="card-text fs-4 fw-bold">{{ number_format($expenses->sum('amount'), 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm summary-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-calendar-check me-2"></i>This Month</h5>
                    <p class="card-text fs-4 fw-bold">{{ number_format($expenses->where('date', '>=', now()->startOfMonth())->sum('amount'), 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info shadow-sm summary-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-receipt me-2"></i>Total Transactions</h5>
                    <p class="card-text fs-4 fw-bold">{{ $expenses->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Expense History</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Mess</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('member.messes.show', $expense->mess) }}" class="text-decoration-none">
                                {{ $expense->mess->name }}
                            </a>
                        </td>
                        <td class="fw-bold">{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ Str::limit($expense->description, 50) }}</td>
                        <td class="text-end">
                            <a href="{{ route('member.expenses.edit', $expense->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('member.expenses.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fa-solid fa-box-open fa-2x mb-2"></i>
                            <p>No expenses recorded yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExpenseModalLabel">Add New Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('member.expenses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mess_id" class="form-label">Mess</label>
                        <select class="form-select" id="mess_id" name="mess_id" required>
                            @foreach(Auth::user()->memberships as $mess)
                                <option value="{{ $mess->id }}">{{ $mess->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title {
    font-weight: 600;
}
.summary-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(event) {
    event.preventDefault();
    const form = event.target;
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endpush
