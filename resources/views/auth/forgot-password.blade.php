@extends('layouts.auth')

@section('title', 'Forgot Password | AutoSpareLink') {{-- SEO title --}}

@section('content')
<div class="container-fluid about py-2">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-md-3"></div>

            <div class="col-md-6">
                <!-- Logo -->
                <img class="mb-4 d-block mx-auto" style="width: 200px;"
                     src="{{ asset('frontend/img/logo.png') }}" alt="AutoSpareLink Logo">

                <!-- Card -->
                <div class="card rounded shadow">
                    <div class="card-header text-center">
                        <strong>Forgot Your Password?</strong>
                    </div>

                    <div class="card-body">
                        <p class="mb-3 text-center">
                            Enter your email address below and we will send you a link to reset your password.
                        </p>

                        <!-- Success message -->
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Form -->
                        <form method="POST" action="/forgot-password">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       required
                                       autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-50">Send Reset Link</button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="/login" class="text-decoration-none">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3"></div>
        </div>
    </div>
</div>
@endsection
