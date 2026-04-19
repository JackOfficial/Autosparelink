@extends('admin.layouts.app')

@section('title', 'Edit User Account | Autosparelink')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 pt-3">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-user-edit mr-2 text-primary"></i>Edit User
            </h1>
            <p class="text-muted small">Update profile details and account permissions for <strong>{{ $user->name }}</strong>.</p>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('admin.users.index') }}" class="btn btn-light border shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Directory
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0">General Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter full name" required>
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="email@example.com" required>
                                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                                <small class="text-muted">Leave empty to keep current password</small>
                                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                            </div>

                            <div class="col-12"><hr class="my-3"></div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase">Account Roles</label>
                                @role('super-admin')
                                    <select name="roles[]" class="form-control select2" multiple data-placeholder="Select Roles">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Super-Admin access only</small>
                                @else
                                    <div class="p-2 border rounded bg-light">
                                        @foreach($user->getRoleNames() as $role)
                                            <span class="badge badge-primary px-2 py-1">{{ ucfirst($role) }}</span>
                                        @endforeach
                                        @if($user->roles->isEmpty()) <span class="text-muted">No roles assigned</span> @endif
                                    </div>
                                    <small class="text-danger"><i class="fas fa-lock mr-1"></i> Role management restricted</small>
                                @endrole
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase">Status</label>
                                <select name="status" class="form-control custom-select" required>
                                    <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active Account</option>
                                    <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Suspended / Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Update Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4 bg-soft-primary">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="card-title font-weight-bold text-primary mb-0">
                        <i class="fas fa-store mr-2"></i>Shop Assignment
                    </h5>
                </div>
                <div class="card-body pt-0">
                    @if($user->shop)
                        <div class="bg-white p-3 rounded shadow-sm border">
                            <h6 class="font-weight-bold mb-1">{{ $user->shop->shop_name }}</h6>
                            <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt mr-1"></i> {{ $user->shop->address ?? 'No address set' }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge badge-success px-2 py-1">Linked Shop</span>
                                <a href="{{ route('admin.shops.show', $user->shop->id) }}" target="_blank" class="small font-weight-bold">
                                    View Shop <i class="fas fa-external-link-alt ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-store-slash fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0 font-italic">No shop currently assigned to this user.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0">Profile Image</h5>
                </div>
                <div class="card-body text-center">
                    <div x-data="{ preview: null }" class="mb-3">
                        <div class="position-relative d-inline-block mb-3">
                            @php
                                $avatarPath = $user->avatar ? asset($user->avatar) : ($user->avatar ?: asset('images/placeholder-user.png'));
                            @endphp
                            <img :src="preview ? preview : '{{ $avatarPath }}'" 
                                 class="rounded-circle shadow-sm border p-1" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        
                        <div class="custom-file text-left">
                            <input type="file" name="photo" class="custom-file-input" id="photoInput" 
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                            <label class="custom-file-label" for="photoInput">Choose new photo</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0">Authentication</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fab fa-google text-danger mr-3 fa-lg"></i>
                        <div>
                            <span class="d-block font-weight-bold small">Google Login</span>
                            @if($user->provider === 'google' || in_array('google', $user->social_providers ?? []))
                                <span class="badge badge-success px-2">Connected</span>
                            @else
                                <span class="badge badge-light border text-muted px-2">Not Linked</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-control { border-radius: 8px; border: 1px solid #e2e8f0; }
    .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    .bg-soft-primary { background-color: #eef5ff; }
</style>
@endpush
@endsection