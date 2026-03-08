@extends('layouts.auth')

@section('title', 'Reset Password | AutoSpareLink') 

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
                        <strong>Reset Your Password</strong>
                    </div>

                    <div class="card-body">

                        <!-- Status message -->
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="/reset-password" method="POST">
                            @csrf

                            <!-- Hidden token -->
                            <input type="hidden" name="token" value="{{ request()->route('token') }}">

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3" x-data="{ show: false }">
                                <label for="password" class="form-label">New Password</label>
                                <input :type="show ? 'text' : 'password'" 
                                       name="password" 
                                       id="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       required>
                                <div class="form-text">
                                    <input type="checkbox" @click="show = !show"> Show password
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password Confirmation -->
                            <div class="mb-3" x-data="{ showConfirm: false }">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input :type="showConfirm ? 'text' : 'password'" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       required>
                                <div class="form-text">
                                    <input type="checkbox" @click="showConfirm = !showConfirm"> Show confirmation
                                </div>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-50">Reset Password</button>
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
