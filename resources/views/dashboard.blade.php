@extends('layouts.app')

@section('content')
<h2 class="mb-4">Owner Dashboard</h2>

<h5 class="mb-3">Mess: {{ $mess->name }} ({{ $mess->location }})</h5>

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Member</th>
            <th>Total Meals</th>
            <th>Total Expenses</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row['member']->name }}</td>
            <td>{{ $row['meals'] }}</td>
            <td>{{ $row['expenses'] }}</td>
            <td>
                <a href="{{ route('owner.members.meals', $row['member']->id) }}" class="btn btn-sm btn-primary">Meals</a>
                <a href="{{ route('owner.members.expenses', $row['member']->id) }}" class="btn btn-sm btn-info">Expenses</a>
                <form action="{{ route('owner.members.destroy', $row['member']->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Remove member?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('owner.members.create') }}" class="btn btn-success mt-3">
    <i class="fa-solid fa-user-plus"></i> Add Member
</a>
@endsection
