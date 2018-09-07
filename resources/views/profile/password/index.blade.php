@extends('layouts.backend.master')

@section('content')
<div class="row">
  <div class="col-lg-4">
    <div class="card card-profile">
      <div class="card-header" style="background-image: url({{ asset('demo/photos/eberhard-grossgasteiger-311213-500.jpg') }});"></div>
      <div class="card-body text-center">
        <img class="card-profile-img" src="{{ asset('demo/faces/male/16.jpg') }}">
        <h3 class="mb-3">{{ auth()->user()->name }}</h3>
        <p class="mb-4">
          {!! $profile->bio !!}
        </p>
        <p class="mb-2">
			<ul class="social-links list-inline mb-0 mt-2">
              <li class="list-inline-item">
                <a href="javascript:void(0)" title="" data-toggle="tooltip" data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
              </li>
              <li class="list-inline-item">
                <a href="javascript:void(0)" title="" data-toggle="tooltip" data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
              </li>
              <li class="list-inline-item">
                <a href="javascript:void(0)" title="" data-toggle="tooltip" data-original-title="1234567890"><i class="fa fa-phone"></i></a>
              </li>
              <li class="list-inline-item">
                <a href="javascript:void(0)" title="" data-toggle="tooltip" data-original-title="@skypename"><i class="fa fa-skype"></i></a>
              </li>
            </ul>
        </p>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <form class="card" method="post" action="{{ route('profile.password.store') }}">
      @csrf
      <div class="card-body">
        <h3 class="card-title">Change Password</h3>
        @if($errors->any())
          <div class="alert alert-danger ">
            <strong>You have following errors</strong>
            <ul style="margin-left: 0px; padding-left: 17px;">
              @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-label">Current Password</label>
              <input type="password" class="form-control" name="current_password" placeholder="**********">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-label">New Password</label>
              <input type="password" class="form-control" name="password" placeholder="**********">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-label">Confirm New Password</label>
              <input type="password" class="form-control" name="password_confirmation" placeholder="**********">
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary">Change Password</button>
      </div>
    </form>
  </div>
</div>
@stop