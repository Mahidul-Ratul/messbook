@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Edit Meal Entry</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Update Meal Entry</h5>
                </div>
                <div class="card-body">
                    @php $authUser = Auth::user(); @endphp
                    <form action="{{ route('owner.daily_meals.update', $dailyMeal->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="mess_id" value="{{ $mess->id }}">

                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control" required value="{{ old('date', $dailyMeal->date) }}">
                        </div>

                        @if($authUser->hasRole('mess_owner'))
                        <div class="mb-3">
                            <label class="form-label">Member</label>
                            <select name="user_id" class="form-select" required>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ (old('user_id', $dailyMeal->user_id) == $member->id) ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="user_id" value="{{ $authUser->id }}">
                        <div class="mb-3">
                            <label class="form-label">Member</label>
                            <input type="text" class="form-control" value="{{ $authUser->name }}" disabled>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Total Meals</label>
                            <input type="number" name="total_meal" class="form-control" required min="0" step="0.5" value="{{ old('total_meal', $dailyMeal->total_meal) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $dailyMeal->notes) }}</textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-1"></i> Update Meal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-title { font-weight: 600; }
</style>
@endpush
