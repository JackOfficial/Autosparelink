@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        {{-- Profile Summary Card --}}
        <div class="col-lg-3 mb-4">
           <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 80px;">
    <div class="card-body text-center bg-light py-5">
        {{-- Avatar / Initial Circle --}}
        <div class="mb-3">
            @if($user->avatar)
                <img src="{{ $user->avatar ?? '' }}" 
                     class="rounded-circle shadow" 
                     style="width: 80px; height: 80px; object-fit: cover; border: 3px solid white;" 
                     alt="{{ $user->name }}">
            @else
                <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center shadow" 
                     style="width: 80px; height: 80px;">
                    <span class="h2 mb-0 text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            @endif
        </div>

        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
        <p class="text-muted small mb-0">{{ $user->email }}</p>
        <div class="badge bg-soft-primary rounded-pill mt-2 px-3 py-2">Customer Account</div>
    </div>
    
    <div class="list-group list-group-flush small fw-bold">
        <a href="{{ route('dashboard.index') }}" class="list-group-item list-group-item-action py-3 border-0">
            <i class="fas fa-arrow-left me-2 text-primary"></i> Back to Dashboard
        </a>
    </div>
</div>
        </div>

        {{-- Edit Forms --}}
        <div class="col-lg-9">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            {{-- FORM 1: Account & Shipping --}}
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Account Details Card --}}
                <div class="card border-0 shadow-sm mb-4 rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-user-circle me-2"></i> Account Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-2">Full Name</label>
                                <input type="text" name="name" class="form-control rounded-pill bg-light border-0 px-3 py-2" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-2">Email Address</label>
                                <input type="email" name="email" class="form-control rounded-pill bg-light border-0 px-3 py-2" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Default Shipping Address Card --}}
<div class="card border-0 shadow-sm mb-4 rounded-4">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0 text-success"><i class="fas fa-truck me-2"></i> Default Shipping Address</h5>
        <p class="small text-muted mb-0">Help us ensure your parts arrive at the right garage.</p>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            {{-- Phone Number --}}
            <div class="col-md-6">
                <label class="small fw-bold text-muted mb-2">Phone Number</label>
                <input type="text" name="phone" class="form-control rounded-pill bg-light border-0 px-3 py-2" 
                    value="{{ old('phone', $address->phone ?? '') }}" 
                    placeholder="e.g. 078XXXXXXX">
            </div>

            {{-- Province (New) --}}
            <div class="col-md-6">
                <label class="small fw-bold text-muted mb-2">Province</label>
                <select name="province" class="form-select rounded-pill bg-light border-0 px-3 py-2">
                    @php $currentProv = old('province', $address->province ?? ''); @endphp
                    <option value="Kigali City" {{ $currentProv == 'Kigali City' ? 'selected' : '' }}>Kigali City</option>
                    <option value="East" {{ $currentProv == 'East' ? 'selected' : '' }}>East</option>
                    <option value="West" {{ $currentProv == 'West' ? 'selected' : '' }}>West</option>
                    <option value="North" {{ $currentProv == 'North' ? 'selected' : '' }}>North</option>
                    <option value="South" {{ $currentProv == 'South' ? 'selected' : '' }}>South</option>
                </select>
            </div>

            {{-- District (New) --}}
            <div class="col-md-6">
                <label class="small fw-bold text-muted mb-2">District</label>
                <input type="text" name="district" class="form-control rounded-pill bg-light border-0 px-3 py-2" 
                    value="{{ old('district', $address->district ?? '') }}" 
                    placeholder="e.g. Nyarugenge or Musanze">
            </div>

            {{-- Street Address --}}
            <div class="col-md-6">
                <label class="small fw-bold text-muted mb-2">Sector</label>
                <input type="text" name="sector" class="form-control rounded-pill bg-light border-0 px-3 py-2" 
                    value="{{ old('sector', $address->sector ?? '') }}" 
                    placeholder="e.g. Kimironko">
            </div>

            {{-- Full Directions --}}
            <div class="col-12">
                <label class="small fw-bold text-muted mb-2">Detailed Street Address / House Number / Landmarks</label>
                <textarea name="street_address" rows="3" class="form-control bg-light border-0 px-3 py-2" 
                    style="border-radius: 15px;" 
                    placeholder="e.g. KN 78 St, near the polyclinic...">{{ old('street_address', $address->street_address ?? '') }}</textarea>
            </div>
        </div>
        
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">
                Save Profile Changes
            </button>
        </div>
    </div>
</div>
            </form>

            {{-- FORM 2: Security --}}
            <div class="card border-0 shadow-sm mb-4 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-danger"><i class="fas fa-shield-alt me-2"></i> Account Security</h5>
                    <p class="small text-muted mb-0 fw-bold">Update your password to keep your account safe.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted mb-2">Current Password</label>
                                <input type="password" name="current_password" class="form-control rounded-pill bg-light border-0 px-3 py-2" placeholder="••••••••" required>
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted mb-2">New Password</label>
                                <input type="password" name="password" class="form-control rounded-pill bg-light border-0 px-3 py-2" placeholder="••••••••" required>
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted mb-2">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-pill bg-light border-0 px-3 py-2" placeholder="••••••••" required>
                            </div>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger border-0 small py-2 px-3 mt-3 rounded-3">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-dark rounded-pill px-4 shadow-sm fw-bold">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection