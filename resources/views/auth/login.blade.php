@extends('layouts.auth')
@section('content')

        <div class="container-fluid about  py-2">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                        <img class="mb-4 d-block mx-auto" style="width: 200px;" src="{{ asset('frontend/img/logo.png') }}" alt="Icon">
                    
                        <div class="card rounded shadow">
                          <div class="card-header">Login to AutoSpareLink</div>
                          <div class="card-body">
                           @error('socialLoginInError')
    <div>
        {{ $message }}
    </div>
                  @enderror

  @if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('status') }}
    </div>
@endif

<div>
  <a class="btn btn-primary mb-0 d-block" href="{{ url('auth/redirect/google') }}">Login with Google</a>
</div>
<div class="text-center mb-1">Or</div>
    <form method="post" action="/login">
      @csrf
      <div class="input-group mb-3">
          <input type="email" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required />
          <div class="input-group-append">
            <div class="input-group-text h-100">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        
        </div>
          @error('email')
        <div class="text-primary" role="alert">
            <strong>{{ $message }}</strong>
        </div>
        @enderror

        <div class="input-group mb-3" x-data="{ show: false }">
          <input :type="show ? 'text' : 'password'" id="psd" name="password" value="{{old('password')}}" class="form-control @error('password') is-invalid @enderror" placeholder="Password" />
          <div class="input-group-append">
            <div class="input-group-text h-100" @click="show = !show">
              <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </div>
          </div>
        </div>
         @error('password')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
        
         <div><a href="/forgot-password">Forgot your password?</a></div>
           <div class="icheck-primary">
              <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
        <hr>    
      <button type="submit" class="btn btn-primary w-50">Login</button>
    </form>
    <div>Not have account yet? <a href="/register">Register</a></div>
                        </div>
                    </div>
                     <div class="col-md-3">
                    </div>
                </div>
            </div>
        </div>
      </div>
        
@endsection