@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        {{-- Reusing the Sidebar from Dashboard --}}
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                <div class="card-body text-center bg-light py-4">
                     <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                        <span class="h4 mb-0 text-white font-weight-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <h6 class="font-weight-bold mb-0">{{ $user->name }}</h6>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>
                <div class="list-group list-group-flush small font-weight-bold">
                    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action py-3">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        {{-- Edit Form --}}
        <div class="col-lg-9">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Account Details Card --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <h5 class="font-weight-bold mb-0 text-primary"><i class="fas fa-user-circle mr-2"></i> Account Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">Full Name</label>
                                <input type="text" name="name" class="form-control rounded-pill bg-light border-0" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">Email Address</label>
                                <input type="email" name="email" class="form-control rounded-pill bg-light border-0" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Default Shipping Address Card --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <h5 class="font-weight-bold mb-0 text-success"><i class="fas fa-truck mr-2"></i> Default Shipping Address</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">Phone Number</label>
                                <input type="text" name="phone" class="form-control rounded-pill bg-light border-0" value="{{ old('phone', $address->phone) }}" placeholder="e.g. 078XXXXXXX">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">City</label>
                                <select name="city" class="form-control rounded-pill bg-light border-0">
                                    <option value="Kigali" {{ $address->city == 'Kigali' ? 'selected' : '' }}>Kigali</option>
                                    <option value="Musanze" {{ $address->city == 'Musanze' ? 'selected' : '' }}>Musanze</option>
                                    <option value="Rubavu" {{ $address->city == 'Rubavu' ? 'selected' : '' }}>Rubavu</option>
                                    <option value="Other" {{ $address->city == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-12 form-group">
                                <label class="small font-weight-bold text-muted">Street / Neighborhood Address</label>
                                <textarea name="street_address" rows="3" class="form-control bg-light border-0" style="border-radius: 15px;">{{ old('street_address', $address->street_address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Security & Password Card --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
        <h5 class="font-weight-bold mb-0 text-danger"><i class="fas fa-shield-alt mr-2"></i> Account Security</h5>
        <p class="small text-muted mb-0 font-weight-bold">Change your password to keep your account safe.</p>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('profile.password.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-4 form-group">
                    <label class="small font-weight-bold text-muted">Current Password</label>
                    <div class="input-group">
                        <input type="password" name="current_password" class="form-control rounded-pill bg-light border-0 px-3" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="col-md-4 form-group">
                    <label class="small font-weight-bold text-muted">New Password</label>
                    <input type="password" name="password" class="form-control rounded-pill bg-light border-0 px-3" placeholder="••••••••" required>
                </div>
                <div class="col-md-4 form-group">
                    <label class="small font-weight-bold text-muted">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-pill bg-light border-0 px-3" placeholder="••••••••" required>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger border-0 small py-2 px-3 mt-2" style="border-radius: 10px;">
                    <ul class="mb-0 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="text-right mt-3">
                <button type="submit" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold btn-sm">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm font-weight-bold">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection