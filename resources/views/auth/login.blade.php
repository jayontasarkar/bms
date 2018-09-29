@extends('layouts.frontend.master')

@section('content')
<div class="row">
    <div class="col col-login mx-auto">
      <div class="text-center mb-6">
        <span style="font-size: 2em; font-weight: bold;">{{ config("bms.site_title") }}</span>
      </div>
      <form class="card" action="{{ route('login') }}" method="post">
        @csrf
        <div class="card-body p-6">
          <div class="card-title">Login to your account</div>
          <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input type="text" 
                   class="form-control{{ invalid($errors->first('username')) }}" 
                   id="username" 
                   name="username" 
                   placeholder="Enter username"
                   autocomplete="off"
                   autofocus="true" 
                   value="{{ old('username') }}" 
            >
            @include('layouts.common.formError', ['key' => 'username'])
          </div>
          <div class="form-group">
            <label class="form-label" for="password">
              Password
            </label>
            <input type="password" 
                   class="form-control{{ invalid($errors->first('password')) }}" 
                   id="password" 
                   placeholder="Password"
                   name="password"
            >
            @include('layouts.common.formError', ['key' => 'password'])
          </div>
          <div class="form-group">
            <label class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" />
              <span class="custom-control-label">Remember me</span>
              <a href="{{ route('password.request') }}" class="float-right small">Forgot your password?</a>
            </label>
          </div>
          <div class="form-footer">
            <button type="submit" class="btn btn-primary btn-block">Log in</button>
          </div>
          <div class="text-center mt-4">
            <a href="http://www.mrigoya.com:2095" target="_blank">Webmail</a>
          </div>
        </div>
      </form>
    </div>
</div>
@stop