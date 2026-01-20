@extends('layouts.auth')
@section('title', 'Verify Email | AutoSpareLink') 
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
                        <strong>Email Verification Required</strong>
                    </div>

                    <div class="card-body text-center">

                        <!-- Success status -->
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success">
                                A new email verification link has been sent to your email address.
                            </div>
                        @endif

                        <!-- Main message -->
                        <p class="mb-3">
                            Thanks for signing up! Before continuing, please verify your email address
                            by clicking the link we just sent to your inbox.
                        </p>

                        <p class="text-muted small">
                            If you don’t see the email, please check your spam or junk folder.
                        </p>

                        <hr>

                        <!-- Resend form -->
                        <p class="mb-2">
                            Didn’t receive the email?
                        </p>

                        <form method="POST" action="/email/verification-notification">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">
                                Resend Verification Email
                            </button>
                        </form>

                        <div class="mt-4">
                            <a href="/login" class="text-decoration-none">
                                Back to Login
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3"></div>
        </div>
    </div>
</div>
@endsection
