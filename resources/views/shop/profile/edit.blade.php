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
                        
                        {{-- Success Message --}}
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

                            {{-- Documents Section (Polymorphic Relationship) --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-file-invoice me-2 text-primary"></i>Business Verification Documents</h6>
                                <div class="row g-3">
                                    @forelse($shop->documents as $doc)
                                        <div class="col-md-4">
                                            <div class="border rounded p-3 bg-light-subtle text-center h-100 shadow-sm">
                                                <label class="small fw-bold text-muted d-block mb-2">{{ $doc->title }}</label>
                                                
                                                <div class="mb-3">
                                                    @if(in_array(strtolower($doc->file_type), ['jpg', 'jpeg', 'png']))
                                                        <i class="fas fa-file-image fa-3x text-info"></i>
                                                    @else
                                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                    @endif
                                                </div>

                                                <div class="d-grid gap-2">
                                                    {{-- Secure download route for 'local' disk files --}}
                                                    <a href="{{ route('profile.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary shadow-sm">
                                                        <i class="fas fa-download me-1"></i> Download
                                                    </a>
                                                    <small class="text-muted" style="font-size: 0.7rem;">
                                                        Submitted: {{ $doc->created_at->format('d M Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-3">
                                            <p class="text-muted small">No documents found. Please contact support if you need to upload verification files.</p>
                                        </div>
                                    @endforelse
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