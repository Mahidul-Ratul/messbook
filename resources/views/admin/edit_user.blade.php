@extends('layouts.app')

@section('content')
<h1>Edit User Role</h1>

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">
    @csrf
    <div class="form-group">
        <label>Role</label>
        <select name="role" class="form-control">
            @foreach($roles as $role)
            <option value="{{ $role }}" {{ $user->hasRole($role) ? 'selected' : '' }}>{{ $role }}</option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-success mt-3">Update</button>
</form>
@endsection
