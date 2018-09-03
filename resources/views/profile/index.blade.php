@extends('layouts.backend.master')

@section('content')
<div class="row">
  <div class="col-lg-4">
    <div class="card card-profile">
      <div class="card-header" style="background-image: url(demo/photos/eberhard-grossgasteiger-311213-500.jpg);"></div>
      <div class="card-body text-center">
        <img class="card-profile-img" src="demo/faces/male/16.jpg">
        <h3 class="mb-3">{{ auth()->user()->name }}</h3>
        <p class="mb-4">
          Big belly rude boy, million dollar hustler. Unemployed.
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
    <form class="card">
      <div class="card-body">
        <h3 class="card-title">Edit Profile</h3>
        <div class="row">
          <div class="col-md-5">
            <div class="form-group">
              <label class="form-label">Company</label>
              <input type="text" class="form-control" disabled="" placeholder="Company" value="Creative Code Inc.">
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="form-group">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" placeholder="Username" value="michael23">
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="form-group">
              <label class="form-label">Email address</label>
              <input type="email" class="form-control" placeholder="Email">
            </div>
          </div>
          <div class="col-sm-6 col-md-6">
            <div class="form-group">
              <label class="form-label">First Name</label>
              <input type="text" class="form-control" placeholder="Company" value="Chet">
            </div>
          </div>
          <div class="col-sm-6 col-md-6">
            <div class="form-group">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-control" placeholder="Last Name" value="Faker">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group mb-0">
              <label class="form-label">About Me</label>
              <textarea rows="3" class="form-control" placeholder="Here can be your description" value="Mike">
              	Oh so, your weak rhyme You doubt I'll bother, reading into it I'll probably won't, left to my own devices
				But that's the difference in our opinions.
			  </textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary">Update Profile</button>
      </div>
    </form>
  </div>
</div>
@stop