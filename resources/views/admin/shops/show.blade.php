@extends('admin.layouts.app')
@section('title', 'Review: ' . $shop->shop_name)

@section('content')
<div class="container-fluid mt-4">
    {{-- Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.shops.index') }}" class="btn btn-sm btn-link text-muted p-0 mb-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Shops
            </a>
            <h2 class="h3 font-weight-bold mb-0 text-dark">Review: {{ $shop->shop_name }}</h2>
        </div>
        
        <div class="d-flex mt-3 mt-md-0">
            @if(!$shop->is_active)
                <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" class="me-2" 
                      onsubmit="return confirm('Approve this shop? The vendor will be notified and can start selling.');">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="fas fa-check-circle me-1"></i> Approve Shop
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST">
                @csrf @method('PUT')
                <button type="submit" class="btn btn-{{ $shop->is_active ? 'danger' : 'outline-primary' }} px-4 shadow-sm">
                    <i class="fas fa-{{ $shop->is_active ? 'ban' : 'play' }} me-1"></i> 
                    {{ $shop->is_active ? 'Suspend Shop' : 'Activate Shop' }}
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: Shop Identity --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <img src="{{ $shop->logo ? asset('storage/' . $shop->logo) : asset('images/default-shop.png') }}" 
                                 class="rounded-circle border p-1 shadow-sm" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <h5 class="font-weight-bold mb-1">{{ $shop->shop_name }}</h5>
                        <span class="badge bg-{{ $shop->is_active ? 'success' : 'warning text-dark' }} px-3">
                            {{ $shop->is_active ? 'Active' : 'Pending Verification' }}
                        </span>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold d-block">Shop Owner</label>
                        <p class="mb-0 fw-bold text-dark">{{ $shop->user->name }}</p>
                        <p class="small text-muted">{{ $shop->user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold d-block">Contact Details</label>
                        <p class="mb-1 small"><i class="fas fa-envelope me-2 text-muted"></i>{{ $shop->shop_email ?? $shop->user->email }}</p>
                        <p class="mb-0 small"><i class="fas fa-phone me-2 text-muted"></i>{{ $shop->phone_number }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold d-block">Business Address</label>
                        <p class="mb-0 small"><i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $shop->address }}</p>
                    </div>

                    <div class="mb-0">
                        <label class="small text-muted text-uppercase fw-bold d-block">TIN Number (RRA)</label>
                        <p class="mb-0 fw-bold text-primary">{{ $shop->tin_number }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Shop Description</h6>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        {{ $shop->description ?? 'No description provided by vendor.' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Right Column: Documentation Verification --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Verification Documents</h5>
                    <span class="badge bg-light text-dark">{{ $shop->documents->count() }} Files</span>
                </div>
                <div class="card-body">
                    @forelse($shop->documents as $doc)
                        <div class="border rounded p-3 mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-7 border-end">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="fas fa-file-contract text-primary fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $doc->title }}</h6>
                                            <span class="small text-muted">{{ strtoupper($doc->file_type) }} • {{ number_format($doc->file_size / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Secure Preview --}}
                                    <div class="document-preview rounded bg-light mb-3 d-flex align-items-center justify-content-center" style="height: 250px; overflow: hidden;">
                                        @if(in_array(strtolower($doc->file_type), ['jpg', 'jpeg', 'png', 'webp']))
                                            {{-- Assuming files are stored in a non-public 'documents' folder --}}
                                            <img src="{{ route('admin.shops.view-doc', $doc) }}" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                        @else
                                            <div class="text-center">
                                                <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                                <p class="fw-bold text-muted">PDF Document Preview Not Available</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-5 text-center">
                                    <p class="small text-muted">Uploaded on: {{ $doc->created_at->format('M d, Y') }}</p>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.shops.view-doc', $doc) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> View Full Size
                                        </a>
                                        <a href="{{ route('admin.shops.download-doc', $doc) }}" class="btn btn-light border btn-sm">
                                            <i class="fas fa-download me-1"></i> Download File
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                            <p class="text-muted">No documents have been uploaded for this shop.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            {{-- Admin Footer --}}
            <div class="card shadow-sm border-0 mt-4 bg-light border-start border-primary" style="border-left-width: 4px !important;">
                <div class="card-body py-3">
                    <p class="mb-0 small text-muted">
                        <i class="fas fa-info-circle me-1 text-primary"></i>
                        <strong>Compliance Checklist:</strong> 1. Verify TIN matches RRA records. 2. Ensure RDB Business Certificate is valid. 3. Check that phone numbers are verified.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .document-preview img { transition: all 0.3s ease; cursor: zoom-in; }
    .document-preview img:hover { transform: scale(1.02); }
</style>
@endsection