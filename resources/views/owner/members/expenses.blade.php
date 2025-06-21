@extends('layouts.app')

@section('content')
<h3>Expenses for {{ $user->name }}</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->date }}</td>
                <td>{{ $expense->amount }}</td>
                <td>{{ $expense->notes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('owner.members.index') }}" class="btn btn-secondary">Back</a>
@endsection
