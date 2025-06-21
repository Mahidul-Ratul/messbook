@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="fw-bold mb-4"><i class="fa-solid fa-user-clock me-2"></i> Join Requests</h2>

    @if($requests->isEmpty())
        <div class="alert alert-info text-center">
            No pending join requests.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Member</th>
                            <th>Mess</th>
                            <th>Requested At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td>{{ $request->user->name }}</td>
                                <td>{{ $request->mess->name }}</td>
                                <td>{{ $request->created_at->format('d M Y') }}</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>
                                    <form action="{{ route('owner.members.approve', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success"><i class="fa-solid fa-check"></i></button>
                                    </form>

                                    <form action="{{ route('owner.members.reject', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-times"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    @endif

</div>

@endsection
