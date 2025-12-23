@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Edit User</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    <!-- Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <!-- Password -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <!-- Roles & Permissions (Only for Super Admin) -->
                    @if(auth()->user()->hasRole('super-admin'))
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Roles</label>
                            <select name="roles[]" class="form-control" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" 
                                        {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Use CTRL (CMD on Mac) to select multiple roles</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Permissions</label>
                            <select name="permissions[]" class="form-control" multiple>
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}" 
                                        {{ $user->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                                        {{ ucfirst($permission->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Assign specific permissions (optional)</small>
                        </div>
                    @endif

                    <!-- Profile Photo -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Profile Photo (optional)</label>
                        <div x-data="{ preview: null }">
                            <input type="file" name="photo" accept="image/*" class="form-control"
                                @change="const file = $event.target.files[0]; preview = file ? URL.createObjectURL(file) : null;">
                            @if($user->photo)
                                <img src="{{ asset($user->photo) }}" class="img-thumbnail mt-2" width="100">
                            @endif
                            <template x-if="preview">
                                <img :src="preview" class="img-thumbnail mt-2" width="100">
                            </template>
                        </div>
                    </div>

                    <!-- Social Login Providers -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Social Login</label>
                        <div class="d-flex gap-2">
                            @foreach(['google','facebook','twitter'] as $provider)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="social_providers[]" value="{{ $provider }}" id="provider-{{ $provider }}"
                                        {{ in_array($provider, $user->social_providers ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="provider-{{ $provider }}">{{ ucfirst($provider) }}</label>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Check the providers the user can use to login via Socialite</small>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="1" {{ $user->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$user->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary mt-2">
                    <i class="fas fa-save"></i> Update User
                </button>

            </form>

        </div>
    </div>
</div>
</section>

@endsection
