<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    <title>{{ config('bms.site_title') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  </head>
  <body>
    <div class="page" id="app">
      <div class="page-main" v-cloak>
        
        @include('layouts.backend.partials._header')

        @include('layouts.backend.partials._navbar')

        <div class="my-3 my-md-5">
          <div class="container">
            @yield('content')
          </div>
        </div>
      </div>
      
      <flash message="{{ session('flash') }}"></flash>

      @include('layouts.backend.partials._footer')

    </div>


    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
  </body>
</html>  