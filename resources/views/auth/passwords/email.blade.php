@extends('layouts.frontend.master')

@section('content')
<div class="row">
    <div class="col col-login mx-auto">
        <div class="text-center mb-6">
            <span style="font-size: 2em; font-weight: bold;">{{ __(config('bms.site_title')) }}</span>
        </div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" class="card" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}">
            @csrf
            <div class="card-body">
                <span style="font-size: 1.5em; position: relative; margin-left: -10px; margin-bottom: 10px;">Reset your login password</span>
                <div class="form-group row">
                <label for="email" class="col-form-label">{{ __('E-Mail Address') }}</label>

                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group row mb-0 mt-4">
                <div class="col-md-6 offset-md-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Send Password Reset Link') }}
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
