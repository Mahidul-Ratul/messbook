@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Edit Mess</h4>
        <a href="{{ route('owner.messes.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to My Messes
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Update Mess Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.messes.update', $mess->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Mess Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $mess->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $mess->location) }}">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description / Rules</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $mess->description) }}</textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
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
