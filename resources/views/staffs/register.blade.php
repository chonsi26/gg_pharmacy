@extends('staffs.layout')

@section('title', 'Staff Registration')
@section('card_class', 'wide')
@section('headline', 'Join the team')
@section('subline', 'Create your staff account to start working with orders, stock and customers.')

@section('content')
    <h1>Create staff account</h1>
    <p class="lede">Fill in your details below. Fields marked optional can be skipped.</p>

    @if ($errors->any())
        <div class="alert alert-err" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('staff.register.submit') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="grid">
            <div class="field">
                <label for="first_name">First name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                       autocomplete="given-name" required autofocus
                       class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}">
            </div>

            <div class="field">
                <label for="last_name">Last name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                       autocomplete="family-name" required
                       class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}">
            </div>

            <div class="field full">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       autocomplete="email" required
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
            </div>

            <div class="field full">
                <label for="contact_number">Mobile number <span class="opt">(optional)</span></label>
                <div class="phone">
                    <span class="prefix">+63</span>
                    <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}"
                           inputmode="numeric" maxlength="10" placeholder="9123456789" autocomplete="tel-national"
                           class="{{ $errors->has('contact_number') ? 'is-invalid' : '' }}">
                </div>
            </div>

            <div class="field full">
                <label for="address">Address <span class="opt">(optional)</span></label>
                <textarea id="address" name="address" maxlength="500" autocomplete="street-address"
                          class="{{ $errors->has('address') ? 'is-invalid' : '' }}">{{ old('address') }}</textarea>
            </div>

            <div class="field full">
                <label for="profile_picture">Profile photo <span class="opt">(optional, max 2 MB)</span></label>
                <input type="file" id="profile_picture" name="profile_picture"
                       accept="image/jpeg,image/png,image/webp"
                       class="{{ $errors->has('profile_picture') ? 'is-invalid' : '' }}">
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="pw">
                    <input type="password" id="password" name="password"
                           autocomplete="new-password" minlength="8" required
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                    <button type="button" data-toggle-password="password" aria-pressed="false">Show</button>
                </div>
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm password</label>
                <div class="pw">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           autocomplete="new-password" minlength="8" required>
                    <button type="button" data-toggle-password="password_confirmation" aria-pressed="false">Show</button>
                </div>
            </div>
        </div>

        <div style="height: 24px"></div>
        <button type="submit" class="btn">Create account</button>
    </form>

    <p class="switch">Already have an account? <a href="{{ route('staff.login') }}">Sign in</a></p>
@endsection