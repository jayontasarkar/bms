@extends('layouts.backend.master')

@section('content')
    @component('layouts.backend.common.page-header') 
      User Management 
      @slot('rightContent')
        <create-user></create-user>
      @endslot
    @endcomponent
    <div class="row row-cards row-deck">
      <div class="col-12">
        <div class="card">
          <div class="table-responsive">
            <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
              <thead>
                <tr>
                  <th class="text-center w-1">
                    <i class="icon-people"></i>
                  </th>
                  <th>Full Name</th>
                  <th>Email Address</th>
                  <th>Username</th>
                  <th>Phone/Mobile</th>
                  <th>Status</th>
                  <th>
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach($users as $user)
                <tr>
                  <td class="text-center">
                    <div class="avatar d-block" style="background-image: url(./../{{ $user->avatar() }})">
                    </div>
                  </td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->username }}</td>
                  <td>{{ $user->phone }}</td>
                  <td>
                    @if($user->status)
                      <span class="badge badge-success">Active</span>
                    @else  
                      <span class="badge badge-danger">Blocked</span>
                    @endif  
                  </td>
                  <td>
                    @if( ! $user->status)
                      <a class="btn btn-xs btn-icon" style="color: #45aaf2;" href="{{ route('users.status', [$user]) }}">
                        <i class="fe fe-check-circle"></i>
                      </a>
                    @else
                      <a class="btn btn-xs btn-icon" style="color: darkred;" href="{{ route('users.status', [$user]) }}">
                        <i class="fe fe-x-circle"></i>
                      </a>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
@stop            