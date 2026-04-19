@extends('admin.layouts.app')

@section('title', 'User Profile | Autosparelink')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 pt-3">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-user-circle mr-2 text-primary"></i>User Profile
            </h1>
            <p class="text-muted small">Detailed account information and activity logs for this user.</p>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('admin.users.index') }}" class="btn btn-light border shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-info shadow-sm ml-2">
                <i class="fas fa-edit mr-1"></i> Edit User
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-4 mb-4">
                <div class="card-body">
                    <div class="position-relative d-inline-block mb-3">
                        @php
                            $avatarPath = $user->photo ? asset($user->photo) : ($user->avatar ?: asset('images/default-user.png'));
                        @endphp
                        <img src="{{ $avatarPath }}" 
                             class="rounded-circle shadow border p-1" 
                             style="width: 140px; height: 140px; object-fit: cover;">
                        
                        <span class="badge {{ $user->status ? 'badge-success' : 'badge-danger' }} position-absolute shadow-sm" 
                              style="bottom: 10px; right: 10px; border: 2px solid white; padding: 5px 10px; border-radius: 50px;">
                            {{ $user->status ? 'ACTIVE' : 'INACTIVE' }}
                        </span>
                    </div>
                    
                    <h4 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h4>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        @forelse($user->getRoleNames() as $role)
                            <span class="badge bg-soft-primary text-primary px-3 py-2 text-uppercase" style="letter-spacing: 0.5px;">
                                <i class="fas fa-user-shield mr-1"></i> {{ $role }}
                            </span>
                        @empty
                            <span class="badge badge-light border text-muted px-3 py-2">NO ROLE</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="font-weight-bold mb-0 text-muted small text-uppercase">Security & Auth</h6>
                </div>
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Google Login</span>
                            @if($user->provider === 'google' || in_array('google', $user->social_providers ?? []))
                                <span class="text-success font-weight-bold">Connected</span>
                            @else
                                <span class="text-muted">Not Linked</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Registration</span>
                            <span class="text-dark">{{ $user->created_at->format('d M, Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0">Account Details</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th class="pl-4 py-3 text-muted small text-uppercase" style="width: 30%;">Full Name</th>
                                    <td class="py-3 font-weight-bold">{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th class="pl-4 py-3 text-muted small text-uppercase">Email Address</th>
                                    <td class="py-3">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th class="pl-4 py-3 text-muted small text-uppercase">Account ID</th>
                                    <td class="py-3"><code class="text-primary font-weight-bold">#USR-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</code></td>
                                </tr>
                                <tr>
                                    <th class="pl-4 py-3 text-muted small text-uppercase">Auth Providers</th>
                                    <td class="py-3">
                                        @if(!empty($user->social_providers))
                                            @foreach($user->social_providers as $provider)
                                                <span class="badge badge-light border px-2 py-1 mr-1">
                                                    <i class="fab fa-{{ $provider }} text-danger"></i> {{ ucfirst($provider) }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-muted italic small">Standard Email Login</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pl-4 py-3 text-muted small text-uppercase">Registered On</th>
                                    <td class="py-3 text-dark">
                                        {{ $user->created_at->format('l, F j, Y') }}
                                        <small class="text-muted d-block">({{ $user->created_at->diffForHumans() }})</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pl-4 py-3 text-muted small text-uppercase">Last Profile Update</th>
                                    <td class="py-3 text-dark">
                                        {{ $user->updated_at->format('d M, Y - H:i') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i> For safety, sensitive details like passwords cannot be viewed here.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-soft-primary { background-color: #eef5ff; }
    .table th { background-color: #fbfcfd; border-right: 1px solid #f1f5f9; }
    .table td, .table th { border-top: 1px solid #f1f5f9; vertical-align: middle; }
    .list-group-item { border-color: #f1f5f9; }
</style>
@endpush
@endsection