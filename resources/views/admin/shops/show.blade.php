@extends('admin.layouts.app')
@section('title', $shop->shop_name)

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.shops.index') }}" class="btn btn-sm btn-link text-muted p-0 mb-2">
                <i class="fas fa-arrow-left mr-1"></i> Back to Shops
            </a>
            <h2 class="h3 font-weight-bold mb-0">Review: {{ $shop->shop_name }}</h2>
        </div>
        <div class="d-flex mt-3 mt-md-0">
            @if(!$shop->is_active)
                <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" class="mr-2">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i> Approve Shop
                    </button>
                </form>
            @endif
            <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST">
                @csrf @method('PUT')
                <button type="submit" class="btn btn-{{ $shop->is_active ? 'danger' : 'outline-primary' }} px-4 shadow-sm">
                    <i class="fas fa-{{ $shop->is_active ? 'ban' : 'play' }} mr-1"></i> 
                    {{ $shop->is_active ? 'Suspend Shop' : 'Activate Shop' }}
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-store fa-2x text-primary"></i>
                        </div>
                        <h5 class="font-weight-bold mb-1">{{ $shop->shop_name }}</h5>
                        <span class="badge badge-{{ $shop->is_active ? 'success' : 'warning text-white' }} px-3">
                            {{ $shop->is_active ? 'Active' : 'Pending Verification' }}
                        </span>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase font-weight-bold">Shop Owner</label>
                        <p class="mb-0 font-weight-bold text-dark">{{ $shop->user->name }}</p>
                        <p class="small text-muted">{{ $shop->user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase font-weight-bold">Contact Details</label>
                        <p class="mb-1"><i class="fas fa-envelope mr-2 text-muted"></i>{{ $shop->shop_email }}</p>
                        <p class="mb-0"><i class="fas fa-phone mr-2 text-muted"></i>{{ $shop->phone_number }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase font-weight-bold">Business Address</label>
                        <p class="mb-0"><i class="fas fa-map-marker-alt mr-2 text-muted"></i>{{ $shop->address }}</p>
                    </div>

                    <div class="mb-0">
                        <label class="small text-muted text-uppercase font-weight-bold">TIN Number</label>
                        <p class="mb-0 font-weight-bold text-primary">{{ $shop->tin_number }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3">Shop Description</h6>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        {{ $shop->description ?? 'No description provided by vendor.' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="font-weight-bold mb-0">Verification Documents</h5>
                </div>
                <div class="card-body">
                    @if($shop->documents->count() > 0)
                        <div class="row">
                            @foreach($shop->documents as $doc)
                                <div class="col-md-6 mb-4">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-light rounded p-2 mr-3">
                                                <i class="fas fa-file-alt text-primary"></i>
                                            </div>
                                            <div class="overflow-hidden">
                                                <h6 class="mb-0 text-truncate font-weight-bold">{{ $doc->title }}</h6>
                                                <span class="small text-muted">{{ strtoupper($doc->file_type) }} • {{ round($doc->file_size / 1024, 2) }} KB</span>
                                            </div>
                                        </div>

                                        @if(in_array(strtolower($doc->file_type), ['jpg', 'jpeg', 'png']))
                                            <div class="document-preview rounded bg-light mb-3" style="height: 200px; overflow: hidden;">
                                                <img src="{{ Storage::disk('local')->url($doc->file_path) }}" class="img-fluid w-100" style="object-fit: cover;">
                                            </div>
                                        @else
                                            <div class="bg-light rounded text-center py-5 mb-3">
                                                <i class="fas fa-file-pdf fa-3x text-muted mb-2"></i>
                                                <p class="small text-muted mb-0">PDF Document</p>
                                            </div>
                                        @endif

                                        <div class="d-flex">
                                            <a href="{{ Storage::disk('local')->url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary flex-fill mr-2">
                                                <i class="fas fa-external-link-alt mr-1"></i> Open
                                            </a>
                                            <a href="{{ Storage::disk('local')->url($doc->file_path) }}" download class="btn btn-sm btn-light border flex-fill">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                            <p class="text-muted">No documents have been uploaded for this shop.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card shadow-sm border-0 mt-4 bg-light border-left border-primary" style="border-left-width: 4px !important;">
                <div class="card-body py-3">
                    <p class="mb-0 small font-italic text-muted">
                        <strong>Admin Tip:</strong> Ensure the TIN number matches the name on the RDB Certificate before approving.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { transition: transform 0.2s; }
    .bg-primary-soft { background-color: rgba(0, 123, 255, 0.1); }
    .document-preview img { transition: transform 0.3s; }
    .document-preview img:hover { transform: scale(1.05); }
</style>
@endsection