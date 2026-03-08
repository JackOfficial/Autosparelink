<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Log in</title>
  <link rel="shortcut icon" href="{{ asset('images/logo/foxx-kennels.png') }}">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('back/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('back/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('back/dist/css/adminlte.min.css') }}">

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="/">HFRO</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg" onclick="toggler()">Sign in to start your session (Admin) </p>

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required />
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          @error('email')
        <span class="text-primary" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" id="psd" name="password" value="{{old('password')}}" class="form-control @error('password') is-invalid @enderror" placeholder="Password" />
          <div class="input-group-append" onclick="toggler()">
            <div class="input-group-text">
              <span class="fas fa-eye"></span>
            </div>
          </div>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
<hr>
      <p class="mb-1">
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
        @endif
      </p>
      <p class="mb-0 d-none">
        New to HFRO? <a href="{{ route('register') }}" class="text-center">{{ __('Register') }}</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('back/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('back/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('back/dist/js/adminlte.min.js') }}"></script>
<script>
  function toggler(){
    let psd = document.getElementsById("psd")
    alert("Hello world");
  }
</script>
</body>
</html>
