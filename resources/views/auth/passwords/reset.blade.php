@extends('layouts.frontend.master')

@section('content')
<div class="row">
    <div class="col col-login mx-auto">
        <div class="text-center mb-6">
            <span style="font-size: 2em; font-weight: bold;">{{ __(config('bms.site_title')) }}</span>
        </div>

        <form method="POST" class="card" action="{{ route('password.request') }}" aria-label="{{ __('Reset Password') }}">
            @csrf
            <div class="card-body">
                <span style="font-size: 1.5em; position: relative; margin-left: -10px; margin-bottom: 10px;">Reset password</span>
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group row">
                    <label for="email" class="col-form-label">{{ __('E-Mail Address') }}</label>
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="password" class="col-form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-form-label">{{ __('Confirm Password') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Reset Password') }}
                        </button>
                        <br><br>
                        <a href="{{ route('login') }}" class="btn btn-secondary ml-2">Back to login</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
