@extends('layouts.auth')
@section('content')

        <div class="container-fluid py-5">
            <div class="container">
                <div class="row g-5">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                        <img class="mb-4 d-block mx-auto" style="width: 200px;" src="{{ asset('frontend/img/logo.png') }}" alt="Icon">
                    
                        <div class="card rounded shadow">
                          <div class="card-header">Sign up For AutoSpareLink</div>
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
  <a class="btn mb-2 d-block" style="background-color: #ccc" href="{{ url('auth/redirect/google') }}">Sign up with Google <i class="fab fa-google fa-lg"></i></a>
</div>
<div class="text-center my-1">Or</div>

<form method="post" action="/register">
      @csrf

       <div class="input-group mb-3">
          <input type="text" name="name" value="{{old('name')}}" class="form-control @error('name') is-invalid @enderror" placeholder="Full name" required />
          <div class="input-group-append">
            <div class="input-group-text h-100">
              <span class="fas fa-user"></span>
            </div>
          </div>
          @error('name')
        <span class="text-primary" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
      </div>

      <div class="input-group mb-3">
          <input type="email" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required />
          <div class="input-group-append">
            <div class="input-group-text h-100">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          @error('email')
        <span class="text-primary" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
      </div>

      <div class="input-group mb-3" x-data="{ show: false }">
          <input :type="show ? 'text' : 'password'" id="psd" name="password" value="{{old('password')}}" class="form-control @error('password') is-invalid @enderror" placeholder="Password" />
          <div class="input-group-append">
            <div class="input-group-text h-100" @click="show = !show">
              <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </div>
          </div>
          @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="input-group mb-3" x-data="{ show: false }">
          <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" value="{{old('password_confirmation')}}" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Re-type your password" />
          <div class="input-group-append">
            <div class="input-group-text h-100" @click="show = !show">
              <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </div>
          </div>
          @error('password_confirmation')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
      <hr>
      <button type="submit" class="btn btn-primary w-100">Register</button>
      <div>Already have account? <a href="/login">Login</a></div>
    </form>
    
                        </div>
                    </div>
                     <div class="col-md-3">
                    </div>
                </div>
            </div>
        </div>
      </div>
        
@endsection
