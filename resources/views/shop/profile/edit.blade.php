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
                            <div class="alert alert-success border-0 small mb-4">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('shop.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                <div class="col-md-4 text-center border-end" 
     x-data="imageViewer('{{ $shop->logo ? asset('storage/' . $shop->logo) : asset('images/default-shop.png') }}')">
    
    <label class="small fw-bold text-muted d-block mb-3">Shop Logo</label>
    
    <div class="mb-3">
        <img :src="imageUrl" 
             class="rounded-circle border p-1 shadow-sm" 
             style="width: 120px; height: 120px; object-fit: cover;"
             alt="Shop Logo Preview">
    </div>

    <input type="file" 
           name="logo" 
           class="form-control form-control-sm" 
           accept="image/*"
           @change="fileChosen">
    
    <div class="small text-muted mt-2">Recommended: 500x500px</div>
</div>

                                <div class="col-md-8">
    <div class="mb-3">
        <label class="small fw-bold text-muted">Business Name</label>
        <input type="text" name="shop_name" class="form-control @error('shop_name') is-invalid @enderror" value="{{ old('shop_name', $shop->shop_name) }}" required>
        @error('shop_name') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="small fw-bold text-muted">TIN Number (RRA)</label>
        <input type="text" name="tin_number" class="form-control @error('tin_number') is-invalid @enderror" value="{{ old('tin_number', $shop->tin_number) }}" placeholder="e.g. 123456789">
        @error('tin_number') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
</div>
                            </div>

                            <hr class="my-4">

                            <div class="mb-3">
                                <label class="small fw-bold text-muted">Shop Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Tell customers about your expertise (e.g., Specialized in Mercedes-Benz and BMW spare parts)...">{{ $shop->description }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small fw-bold text-muted">Public Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control" value="{{ $shop->phone_number }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small fw-bold text-muted">Store Location / Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ $shop->address }}" placeholder="e.g. Nyarugenge, Kigali" required>
                                </div>
                            </div>

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
                                    <span class="h5 fw-bold text-dark mb-0">{{ $shop->commission_rate ? number_format($shop->commission_rate) . '%' : 'Standard' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
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