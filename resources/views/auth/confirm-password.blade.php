@extends('layouts.auth')

@section('title', 'Confirm Password | AutoSpareLink') {{-- SEO title --}}

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
                        <strong>Confirm Your Password</strong>
                    </div>

                    <div class="card-body">

                        <p class="mb-3 text-center">
                            Please confirm your password before continuing.
                        </p>

                        <!-- Form -->
                        <form action="/user/confirm-password" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-50">Confirm</button>
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
