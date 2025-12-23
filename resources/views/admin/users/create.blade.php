@extends('admin.layouts.app')

@section('title', 'Add New User')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Add New User</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <!-- Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <!-- Password -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <!-- Roles -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Assign Roles</label>
                        <select name="roles[]" class="form-control" multiple required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Use CTRL (CMD on Mac) to select multiple roles</small>
                    </div>

                    <!-- Profile Photo -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Profile Photo (optional)</label>
                        <div x-data="{ preview: null }">
                            <input type="file" name="photo" accept="image/*" class="form-control"
                                @change="
                                    const file = $event.target.files[0];
                                    preview = file ? URL.createObjectURL(file) : null;
                                ">
                            <template x-if="preview">
                                <img :src="preview" class="img-thumbnail mt-2" width="100">
                            </template>
                        </div>
                    </div>

                    <!-- Social Login Providers -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Social Login (optional)</label>
                        <div class="d-flex gap-2">
                            @foreach(['google','facebook','twitter'] as $provider)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="social_providers[]" value="{{ $provider }}" id="provider-{{ $provider }}">
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
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary mt-2">
                    <i class="fas fa-user-plus"></i> Create User
                </button>

            </form>

        </div>
    </div>
</div>
</section>

@endsection
