<x-shop-dashboard>
    <x-slot:title>Shop Settings</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-dark">Store Identity & Information</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        @if(session('success'))
                            <div class="alert alert-success border-0 shadow-sm small mb-4">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('shop.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                {{-- Logo Upload --}}
                                <div class="col-md-4 text-center border-end" 
                                     x-data="imageViewer('{{ $shop->logo ? asset('storage/' . $shop->logo) : asset('images/default-shop.png') }}')">
                                    <label class="small fw-bold text-muted d-block mb-3">Shop Logo</label>
                                    <div class="mb-3">
                                        <img :src="imageUrl" class="rounded-circle border p-1 shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                                    </div>
                                    <input type="file" name="logo" class="form-control form-control-sm" accept="image/*" @change="fileChosen">
                                </div>

                                {{-- Main Details --}}
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="small fw-bold text-muted">Business Name</label>
                                        <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name', $shop->shop_name) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small fw-bold text-muted">TIN Number (RRA)</label>
                                        <input type="text" name="tin_number" class="form-control" value="{{ old('tin_number', $shop->tin_number) }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Documents Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-file-shield me-2 text-primary"></i>Business Verification Documents
                                </h6>
                                <div class="row g-3">
                                    @php
                                        // Define the expected documents for a Rwanda-based business
                                        $expectedDocs = [
                                            'RDB Certificate' => 'rdb_certificate',
                                            'VAT/TIN Certificate' => 'tin_certificate',
                                            'Owner ID/Passport' => 'owner_id'
                                        ];
                                    @endphp

                                    @foreach($expectedDocs as $title => $slug)
                                        @php 
                                            // Find if this specific document type exists in the polymorphic relation
                                            $existingDoc = $shop->documents->where('title', $title)->first(); 
                                        @endphp
                                        
                                        <div class="col-md-4">
                                            <div class="border rounded p-3 bg-light-subtle h-100 shadow-sm d-flex flex-column justify-content-between">
                                                <div>
                                                    <label class="small fw-bold text-muted d-block mb-2">{{ $title }}</label>
                                                    
                                                    @if($existingDoc)
                                                        {{-- File Found: Show Download & UI --}}
                                                        <div class="text-center py-2 mb-2 bg-white rounded border border-dashed">
                                                            @if(in_array(strtolower($existingDoc->file_type), ['jpg', 'jpeg', 'png']))
                                                                <i class="fas fa-file-image fa-2x text-info"></i>
                                                            @else
                                                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                            @endif
                                                            <div class="mt-2">
                                                                <a href="{{ route('shop.documents.download', $existingDoc->id) }}" class="btn btn-sm btn-link text-decoration-none fw-bold p-0">
                                                                    <i class="fas fa-download me-1"></i>Download Current
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{-- File Missing: Show Warning --}}
                                                        <div class="text-center py-2 mb-2 bg-warning-subtle rounded border border-warning border-dashed">
                                                            <i class="fas fa-exclamation-triangle text-warning mb-1"></i>
                                                            <span class="d-block small fw-bold text-warning-emphasis">Missing Document</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Always show upload input for updates/new uploads --}}
                                                <div class="mt-2">
                                                    <label class="x-small text-muted mb-1" style="font-size: 0.7rem;">
                                                        {{ $existingDoc ? 'Replace Document' : 'Upload Document' }}
                                                    </label>
                                                    <input type="file" name="{{ $slug }}" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Description --}}
                            <div class="mb-3">
                                <label class="small fw-bold text-muted">Shop Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $shop->description) }}</textarea>
                            </div>

                            {{-- Contact & Location --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small fw-bold text-muted">Public Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $shop->phone_number) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small fw-bold text-muted">Store Location / Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $shop->address) }}" required>
                                </div>
                            </div>

                            {{-- Status Bar --}}
                            <div class="card bg-light border-0 mt-4">
                                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="small fw-bold text-muted d-block text-uppercase">Current Status</span>
                                        @if($shop->is_active)
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Verified Merchant</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Verification Pending</span>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="small fw-bold text-muted d-block text-uppercase">Platform Fee</span>
                                        <span class="h5 fw-bold text-dark mb-0">
                                            {{ $shop->commission_rate ? number_format($shop->commission_rate) . '%' : 'Standard' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 text-end">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                    <i class="fas fa-save me-2"></i> Save Profile Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function imageViewer(defaultUrl) {
            return {
                imageUrl: defaultUrl,
                fileChosen(event) {
                    this.fileToDataUrl(event, src => this.imageUrl = src)
                },
                fileToDataUrl(event, callback) {
                    if (! event.target.files.length) return
                    let file = event.target.files[0],
                        reader = new FileReader()
                    reader.readAsDataURL(file)
                    reader.onload = e => callback(e.target.result)
                }
            }
        }
    </script>
    @endpush
</x-shop-dashboard>