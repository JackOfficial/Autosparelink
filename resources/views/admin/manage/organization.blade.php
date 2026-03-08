@extends('admin.layouts.app')
@section('title', 'Organization')
@section('content')
<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Organization Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Organization</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Organization Information</h3>
            @if($organization->count() == 0)
            <a href="{{ route('admin.organization.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Add Details
            </a>
            @endif
        </div>

        <div class="card-body">
            @forelse($organization as $org)
            <div class="row">
                <div class="col-md-8">
                    <fieldset class="border p-3 mb-3">
                        <legend class="w-auto px-2">Basic Information</legend>
                        <p><strong>Name:</strong> {{ $org->name }}</p>
                        <p><strong>Mission:</strong> {!! $org->mission !!}</p>
                        <p><strong>Vision:</strong> {!! $org->vision !!}</p>
                        <p><strong>About:</strong> {!! $org->about !!}</p>
                    </fieldset>

                    <fieldset class="border p-3 mb-3">
                        <legend class="w-auto px-2">Contact Details</legend>
                        <p><strong>Email:</strong> {{ $org->email }}</p>
                        <p><strong>Phone:</strong> {{ $org->phone }}</p>
                        <p><strong>Address:</strong> {{ $org->address }}</p>
                        <p><strong>Website:</strong> 
                            @if($org->website)
                            <a href="{{ $org->website }}" target="_blank">{{ $org->website }}</a>
                            @else
                            N/A
                            @endif
                        </p>
                    </fieldset>
                </div>

                <div class="col-md-4 text-center">
                    <fieldset class="border p-3 mb-3">
                        <legend class="w-auto px-2">Logo</legend>
                        @if($org->logo)
                        <img src="{{ asset('storage/'.$org->logo) }}" alt="{{ $org->name }}" class="img-fluid img-thumbnail">
                        @else
                        <p>No logo uploaded</p>
                        @endif
                    </fieldset>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('admin.organization.edit', $org->id) }}" class="btn btn-info btn-sm mr-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.organization.destroy', $org->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
            @empty
            <div class="alert alert-warning text-center">
                No Organizational Details available at the moment.
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
