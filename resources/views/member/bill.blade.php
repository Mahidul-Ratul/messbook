@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Bill</h1>

    <div class="card mt-3">
        <div class="card-body">
            <pre>{{ json_encode($billData, JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
</div>
@endsection
