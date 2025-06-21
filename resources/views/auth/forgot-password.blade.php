@extends('layouts.guest')

@section('content')

<div class="container py-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center fw-bold">Forgot Your Password?</h2>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group mb-3">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required autofocus value="{{ old('email') }}">
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Send Password Reset Link</button>

        <div class="text-center">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </form>
</div>

@endsection
