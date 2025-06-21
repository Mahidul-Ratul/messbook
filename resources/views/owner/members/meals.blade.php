@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4 text-primary fw-bold">Meals for {{ $member->name }}</h3>

    {{-- Summary Card --}}
    <div class="alert alert-info fw-bold">
        Total Meals for {{ $member->name }}: {{ $totalMeals }}
    </div>

    {{-- Add Meal Form --}}
    <form action="{{ route('owner.members.meals.store', $member->id) }}" method="POST" class="card shadow-sm p-4 border-0 mb-4">
        @csrf

        {{-- Hidden Mess --}}
        <input type="hidden" name="mess_id" value="{{ $mess->id }}">

        <div class="mb-3">
            <label for="date" class="form-label">Meal Date</label>
            <input type="date" name="date" class="form-control" required value="{{ old('date', date('Y-m-d')) }}">
            @error('date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="meal_count" class="form-label">Number of Meals</label>
            <input type="number" class="form-control" id="meal_count" name="meal_count" value="{{ old('meal_count', 1) }}" required min="0">
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes (optional)</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-bold">Save Meal</button>
    </form>

    {{-- Meal History Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Meals</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meals as $meal)
                        <tr>
                            <td>{{ $meal->date }}</td>
                            <td>{{ $meal->meal_count }}</td>
                            <td>{{ $meal->notes }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No meals recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
