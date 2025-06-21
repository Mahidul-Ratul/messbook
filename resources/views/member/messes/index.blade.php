@extends('layouts.app')

@section('content')
<h1>Available Messes</h1>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Mess Name</th>
            <th>Location</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($messes as $mess)
        <tr>
            <td>{{ $mess->name }}</td>
            <td>{{ $mess->location }}</td>
            <td>
                @if(in_array($mess->id, $joined))
                    <button class="btn btn-secondary btn-sm" disabled>Already Joined</button>
                @else
                    <form action="{{ route('member.messes.request-join', $mess->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-primary btn-sm">Join</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
