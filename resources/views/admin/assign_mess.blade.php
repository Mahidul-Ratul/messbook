@extends('layouts.app')

@section('content')
<h3>Assign Mess To {{ $user->name }}</h3>

<form method="POST" action="{{ route('admin.users.assign-mess.store', $user->id) }}">
    @csrf

    <div class="form-group mb-3">
        <label>Select Mess</label>
        <select name="mess_id" class="form-control" required>
            @foreach($messes as $mess)
            <option value="{{ $mess->id }}">{{ $mess->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-success">Assign</button>
</form>
@endsection
