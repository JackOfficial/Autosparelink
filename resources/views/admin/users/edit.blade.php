@extends('admin.layouts.app')

@section('title', 'Edit User Account | Autosparelink')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 pt-3">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-user-edit mr-2 text-primary"></i>Edit User
            </h1>
            <p class="text-muted small">Update profile details and permissions for <strong>{{ $user->name }}</strong>.</p>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('admin.users.index') }}" class="btn btn-light border shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Directory
            </a>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">General Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                                <small class="text-muted">Leave empty to keep current</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                            </div>

                            <div class="col-12"><hr class="my-3"></div>

                            {{-- Professional Checkbox Grid for Roles --}}
                            <div class="col-12 mb-3">
                                <label class="font-weight-bold small text-uppercase d-block mb-3">Account Roles</label>
                                @role('super-admin')
                                    <div class="role-grid">
                                        @foreach($roles as $role)
                                            <div class="role-item">
                                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                                       id="role_{{ $role->id }}" class="role-checkbox"
                                                       {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                <label for="role_{{ $role->id }}" class="role-label shadow-sm">
                                                    <div class="d-flex align-items-center">
                                                        <div class="custom-check-icon mr-2">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                        <span class="font-weight-bold">{{ ucfirst($role->name) }}</span>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted">Select one or more permissions for this user.</small>
                                @else
                                    <div class="p-2 border rounded bg-light">
                                        @foreach($user->getRoleNames() as $role)
                                            <span class="badge badge-primary px-2 py-1">{{ ucfirst($role) }}</span>
                                        @endforeach
                                        @if($user->roles->isEmpty()) <span class="text-muted">No roles assigned</span> @endif
                                    </div>
                                    <small class="text-danger mt-1 d-block"><i class="fas fa-lock mr-1"></i> Role management restricted</small>
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
                    </div>
                </div>
            </div>

            {{-- Right Sidebar --}}
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
                                <p class="text-muted small mb-0"><i class="fas fa-map-marker-alt mr-1"></i> {{ $user->shop->address ?? 'No address' }}</p>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted small mb-0 font-italic">No shop assigned.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">Profile Image</h5>
                    </div>
                    <div class="card-body text-center" x-data="{ preview: null }">
                        <div class="position-relative d-inline-block mb-3">
                            <img :src="preview ? preview : '{{ $user->avatar ? asset($user->avatar) : asset('images/placeholder-user.png') }}'" 
                                 class="rounded-circle shadow-sm border p-1" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <div class="custom-file">
                            <input type="file" name="photo" class="custom-file-input" id="photoInput" @change="preview = URL.createObjectURL($event.target.files[0])">
                            <label class="custom-file-label" for="photoInput text-truncate">Choose</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .form-control { border-radius: 8px; border: 1px solid #e2e8f0; height: calc(1.5em + 1rem + 2px); }
    .bg-soft-primary { background-color: #eef5ff; }
    
    /* Role Grid Styling */
    .role-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; }
    .role-checkbox { display: none; } /* Hide the actual box */
    .role-label {
        display: block;
        padding: 12px 15px;
        background: #fff;
        border: 2px solid #edf2f7;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 0;
    }
    .role-checkbox:checked + .role-label {
        border-color: #3b82f6;
        background-color: #f0f7ff;
        color: #3b82f6;
    }
    .custom-check-icon {
        width: 18px; height: 18px;
        border: 2px solid #cbd5e0;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: transparent;
        transition: 0.2s;
    }
    .role-checkbox:checked + .role-label .custom-check-icon {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
    }
    .role-label:hover { border-color: #cbd5e0; transform: translateY(-1px); }
</style>
@endpush
@endsection