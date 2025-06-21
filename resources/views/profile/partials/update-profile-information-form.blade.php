<section>
    <header>
        <h5 class="card-title">{{ __('Profile Information') }}</h5>
        <p class="card-subtitle mb-4 text-muted">{{ __("Update your account's profile information and email address.") }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
            @error('name')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-muted">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="btn btn-link p-0 text-decoration-none">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 small text-success">{{ __('A new verification link has been sent to your email address.') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">{{ __('Phone') }}</label>
            <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone', $user->phone) }}">
            @error('phone')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">{{ __('Address') }}</label>
            <textarea id="address" name="address" class="form-control">{{ old('address', $user->address) }}</textarea>
            @error('address')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="avatar" class="form-label">{{ __('Avatar') }}</label>
            <div class="d-flex align-items-center">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=80&d=mp' }}" alt="Avatar" class="rounded-circle me-3" width="80" height="80">
                <input id="avatar" name="avatar" type="file" class="form-control" accept="image/*">
            </div>
            @error('avatar')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p class="text-success small mb-0">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
