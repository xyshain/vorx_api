<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>VORX - RTO</title>

  {{-- favicon --}}
  <link rel="icon" href="{{asset('/images/favicon.png') }}" type="image/x-icon"/>

  <!-- Custom fonts for this template-->
  <link href="{{ asset('/sb-admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{ asset('sb-admin/css/sb-admin-2.css') }}" rel="stylesheet">

  <style>
  body{height: 100vh;}
  </style>

</head>

<body class="bg-gradient-{{$app_settings[0]->app_color}}">

  <div class="container h-100">

    <!-- Outer Row -->
    <div class="row align-items-center justify-content-center h-100">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row p-0">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6" style="background: #dddfeb;">
                <div class="px-5 py-4">
                  <div class="text-center">
                    @if(!in_array($app_settings[0]->logo_img, ['', null]))
                      @php
                      $orgLogo = $app_settings[0]->logo_img;
                      $w = 75;
                      if(strpos($orgLogo, '_resize') !== false){
                        $orgLogo = str_replace('_resize', '', $app_settings[0]->logo_img);
                        $w = 50;
                      }
                      @endphp
                      <img class="img-profile img-fluid w-{{$w}}" src="{{ asset('/storage/config/images/'.$orgLogo) }}">
                    @else
                    <img src="{{ asset('/images/logo/vorx_logo-colored_1.png') }}" alt="" class="img-fluid w-50 mb-4">
                    @endif
                    
                    {{-- <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1> --}}
                    <div class="clearfix" style="height: 50px"></div>
                  </div>

                  <div class="alert alert-danger print-error-msg" style="display:none;padding:0; font-size:11px;">
                      <ul style="margin-bottom:0;"></ul>
                  </div>
                  <form class="user" method="POST" action="{{ route('login') }}">
                      @csrf
                    <div class="form-group">
                      {{-- <input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..."> --}}
                      {{-- <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email Address..."> --}}
                      <input type="text" class="form-control form-control-user" id="exampleInputUser" aria-describedby="userHelp" style="border-color:transparent !important" name="username" placeholder="Enter Username...">
                      @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      {{-- <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password"> --}}
                      <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" style="border-color:transparent !important" name="password" required autocomplete="current-password" placeholder="Password">
                      @error('password')
                        <span class="invalid-feedback" role="alert">
                            <span></span>
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-{{$app_settings[0]->app_color}} btn-user btn-block btn-login">
                      {{ __('Login') }} 
                    </button>
                    {{-- <a href="index.html" class="btn btn-primary btn-user btn-block">
                      Login
                    </a> --}}
                    {{-- <hr>
                    <a href="index.html" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="index.html" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a> --}}
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.html">Create an Account!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('/sb-admin/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('/sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ asset('/sb-admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ asset('/sb-admin/js/sb-admin-2.min.js') }}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('.btn-login').on('click', function(e) {
              var $this = $(this);
              var timer = 10000;
              var loadingText = `<div class="spinner-border text-light spinner-border-sm" role="status">
                                  <span class="sr-only"> </span>
                                </div> <span>Loading...</span>`;
                                
              if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
              }
              setTimeout(function() {
                $this.html($this.data('original-text'));
              }, timer);
        });
  })
  </script>
</body>

</html>
