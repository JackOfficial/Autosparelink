@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')

@push('styles')
<style>
    :root { 
        --primary-blue: #0061f2; 
        --dark-steel: #212529; 
        --soft-bg: #f8fafc;
        --border-color: #e2e8f0;
        --orange-main: #ff8a00;
    }
    
    .container-custom { max-width: 1320px; margin: 0 auto; }
    .sticky-sidebar { position: sticky; top: 2rem; }

    /* --- FIXED GALLERY STYLES --- */
    .gallery-container { 
        background: #fff; border-radius: 16px; border: 1px solid var(--border-color); 
        overflow: hidden; transition: box-shadow 0.3s ease; position: relative;
    }
    .main-image-viewport { 
        height: 520px; display: flex; align-items: center; justify-content: center; 
        background: #fff; padding: 1.5rem; position: relative; overflow: hidden;
    }
    .main-image-viewport img { 
        max-height: 100%; width: auto; object-fit: contain; 
        transition: all 0.4s ease;
    }

    /* Navigation Arrows */
    .gallery-nav {
        position: absolute; top: 50%; width: 100%; display: flex;
        justify-content: space-between; padding: 0 20px; transform: translateY(-50%);
        pointer-events: none; z-index: 10;
    }
    .nav-btn {
        width: 45px; height: 45px; border-radius: 50%; background: rgba(255,255,255,0.9);
        border: 1px solid var(--border-color); display: flex; align-items: center;
        justify-content: center; color: var(--dark-steel); cursor: pointer;
        pointer-events: auto; transition: 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .nav-btn:hover { background: var(--primary-blue); color: #fff; }

    /* Thumbnail Strip */
    .thumb-strip {
        display: flex; gap: 10px; padding: 15px; background: var(--soft-bg);
        border-top: 1px solid var(--border-color); overflow-x: auto;
    }
    .thumb-item {
        width: 70px; height: 70px; border-radius: 8px; border: 2px solid transparent;
        background: #fff; padding: 5px; cursor: pointer; transition: 0.2s; flex-shrink: 0;
    }
    .thumb-item img { width: 100%; height: 100%; object-fit: contain; }
    .thumb-item.active-thumb { border-color: var(--primary-blue); box-shadow: 0 0 0 2px rgba(0,97,242,0.1); }
    /* --- END GALLERY FIXES --- */

    /* Rest of your existing styles preserved */
    .tech-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .tech-table thead th { 
        background: var(--soft-bg); color: #64748b; font-weight: 700; 
        text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; padding: 1rem 1.2rem;
    }
    .tech-table tbody td { padding: 1rem 1.2rem; border-bottom: 1px solid var(--soft-bg) !important; }
    .brand-badge { background: var(--primary-blue); color: #fff; padding: 5px 12px; border-radius: 6px; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; }
    .price-text { font-size: 1.1rem; font-weight: 800; color: #000; }
    .sub-image-wrapper { width: 64px; height: 64px; background: #fff; border: 1px solid var(--border-color); border-radius: 12px; display: flex; align-items: center; justify-content: center; padding: 6px; }
    .sub-image-wrapper img { max-width: 100%; max-height: 100%; object-fit: contain; }

    .x-small { 
        font-size: 0.6rem; 
        padding: 2px 6px; 
        border-radius: 4px; 
        letter-spacing: 0.03em;
        font-weight: 700;
    }
    .gap-1 { gap: 4px; }
    .badge-success { background-color: #dcfce7; color: #166534; }
    .badge-warning { background-color: #fef9c3; color: #854d0e; }
    .badge-info { background-color: #e0f2fe; color: #075985; }
    .badge-primary { background-color: #e0e7ff; color: #3730a3; }

    /* New Shop Styling */
    .shop-avatar {
        width: 32px;
        height: 32px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }
    
    .part-link { color: #1e293b; text-decoration: none; transition: 0.2s; }
    .part-link:hover { color: var(--primary-blue); }

    .btn-outline-primary {
        border-width: 2px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    /* --- INTERACTIVE STAR RATING STYLES --- */
    .star-rating input { display: none; }
    .star-rating label {
        font-size: 1.5rem;
        color: #cbd5e1;
        cursor: pointer;
        margin-right: 6px;
        transition: color 0.2s ease-in-out;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffb800;
    }

    @media (max-width: 991px) {
        .main-image-viewport { height: 350px; }
        .sticky-sidebar { position: relative; top: 0; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid container-custom mt-3">

    {{-- 1. Modern Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/" class="text-muted text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="/shop" class="text-muted text-decoration-none">Catalog</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold" aria-current="page">{{ $part->part_name }}</li>
        </ol>
    </nav>

    <div class="row gx-lg-5">
        {{-- 2. IMAGE GALLERY --}}
        <div class="col-lg-7 mb-4">
            <div class="gallery-container shadow-sm" 
                 x-data="{ 
                    index: 0, 
                    images: {{ Js::from($photos->isNotEmpty() ? $photos->pluck('file_path')->map(fn($p) => asset('storage/'.$p)) : [asset('frontend/img/placeholder.jpg')]) }} 
                 }">
                
                <div class="main-image-viewport">
                    {{-- Main Dynamic Image --}}
                    <template x-for="(img, i) in images" :key="i">
                        <img x-show="index === i" 
                             :src="img" 
                             alt="{{ $part->part_name }}"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             style="position: absolute;">
                    </template>
                    
                    {{-- Navigation Arrows --}}
                    <div class="gallery-nav" x-show="images.length > 1">
                        <button class="nav-btn" @click="index = (index - 1 + images.length) % images.length">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <button class="nav-btn" @click="index = (index + 1) % images.length">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                {{-- Thumbnail Strip --}}
                <div class="thumb-strip" x-show="images.length > 1">
                    <template x-for="(img, i) in images" :key="i">
                        <div class="thumb-item" 
                             :class="{ 'active-thumb': index === i }" 
                             @click="index = i">
                            <img :src="img" loading="lazy">
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- 3. PRODUCT INFO (Sticky Sidebar) --}}
        <div class="col-lg-5">
            <div class="sticky-sidebar">
                {{-- Livewire Component handles Price, Stock, and Add-to-Cart logic --}}
                @livewire('product-info', ['part' => $part])

                <div class="mt-4 p-3 rounded-xl bg-light border">
                    <div class="d-flex align-items-center text-muted small">
                        <i class="fas fa-shipping-fast mr-2"></i> Fast Delivery available across Rwanda
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. SUBSTITUTIONS --}}
    @if($substitutions->isNotEmpty())
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <div class="icon-box-sm bg-orange-soft mr-3">
                        <i class="fas fa-exchange-alt text-orange"></i>
                    </div>
                    <h4 class="section-title mb-0">Alternative Replacements</h4>
                </div>
                <span class="badge badge-soft-primary px-3 py-2 rounded-pill small font-weight-bold">
                    {{ $substitutions->count() }} Options Available
                </span>
            </div>
            
            <div class="card tech-card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                <div class="table-responsive">
                    <table class="table tech-table mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 pl-4">Product & Specification</th>
                                <th class="py-3">Brand</th>
                                <th class="py-3">Vendor / Shop</th>
                                <th class="py-3">Stock Status</th>
                                <th class="py-3 text-right">Price (RWF)</th>
                                <th class="py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($substitutions as $sub)
                            <tr>
                                <td class="pl-4 py-3" style="min-width: 300px;">
                                    <div class="d-flex align-items-center">
                                        <div class="sub-image-wrapper">
                                            <img src="{{ $sub->photos->first() ? asset('storage/' . $sub->photos->first()->file_path) : asset('frontend/img/placeholder.jpg') }}" 
                                                 alt="{{ $sub->part_name }}">
                                        </div>
                                        <div class="ml-3">
                                            <a href="{{ route('spare-parts.show', $sub->sku) }}" class="part-link font-weight-bold">
                                                {{ $sub->part_number }}
                                            </a>
                                            <div class="text-muted small mb-1">{{ Str::limit($sub->part_name, 30) }}</div>
                                            
                                            <div class="d-flex gap-1 flex-wrap">
                                                @php
                                                    $stateSlug = $sub->state ? strtolower($sub->state->slug) : 'new';
                                                    $stateClass = [
                                                        'new'         => 'badge-success',
                                                        'used'        => 'badge-warning',
                                                        'refurbished' => 'badge-info'
                                                    ][$stateSlug] ?? 'badge-secondary';
                                                @endphp
                                                <span class="badge {{ $stateClass }} x-small text-uppercase">
                                                    {{ $sub->state->name ?? 'New' }}
                                                </span>

                                                <span class="badge {{ $sub->is_genuine ? 'badge-primary' : 'badge-light' }} x-small text-uppercase">
                                                    {{ $sub->is_genuine ? 'GENUINE O.E.M' : 'AFTERMARKET' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="manufacturer-tag small font-weight-bold text-uppercase">
                                        {{ $sub->partBrand->name ?? 'Generic' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="shop-avatar mr-2">
                                            <i class="fas fa-store text-muted"></i>
                                        </div>
                                        <div>
                                            <a href="#" class="text-dark small font-weight-bold d-block mb-0">
                                                {{ $sub->shop->name ?? 'AutoLink Official' }}
                                            </a>
                                            <div class="text-warning small" style="font-size: 0.7rem;">
                                                <i class="fas fa-star"></i> 4.8 (120+ sales)
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="stock-indicator {{ $sub->stock_quantity > 0 ? 'is-in' : 'is-out' }}">
                                        <span class="dot"></span>
                                        {{ $sub->stock_quantity > 0 ? $sub->stock_quantity . ' in stock' : 'Out of Stock' }}
                                    </div>
                                </td>

                                <td class="text-right pr-4">
                                    @if($sub->old_unit_price && $sub->old_unit_price > $sub->unit_price)
                                        <del class="text-muted small d-block" style="font-size: 0.7rem;">{{ number_format($sub->old_unit_price, 0) }}</del>
                                    @endif
                                    <span class="price-text text-primary">{{ number_format($sub->unit_price ?? $sub->price, 0) }}</span>
                                </td>

                                <td class="text-center pr-4">
                                    <a href="{{ route('spare-parts.show', $sub->sku) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 5. COMPATIBILITY --}}
    @if($compatibilities->isNotEmpty())
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-box-sm bg-primary-soft mr-3">
                    <i class="fas fa-car-side text-primary"></i>
                </div>
                <h4 class="section-title mb-0">Exact Fitment Guide</h4>
            </div>
            
            <div class="card tech-card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                <div class="table-responsive">
                    <table class="table tech-table mb-0">
                        <thead class="bg-soft-blue">
                            <tr>
                                <th class="py-3 pl-4">Vehicle Make</th>
                                <th class="py-3">Model & Series</th>
                                <th class="py-3">Engine / Trim</th>
                                <th class="py-3 text-center">Production Years</th>
                                <th class="py-3 text-center">Shop Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compatibilities as $fitment)
                            @php
                                $spec = $fitment->specification;
                                $model = $spec->vehicleModel;
                                $variant = $spec->variant;
                                
                                $params = [
                                    'brand'   => $model?->brand?->slug ?? 'all',
                                    'model'   => $model?->slug ?? 'all',
                                    'variant' => $variant?->slug ?? 'all'
                                ];

                                if (request('search')) {
                                    $params['search'] = request('search');
                                }
                            @endphp
                            <tr class="hover-row"> 
                                <td class="pl-4">
                                    <span class="brand-badge">{{ $model?->brand?->brand_name ?? '—' }}</span>
                                </td>

                                <td>
                                    <div class="model-text">
                                        {{ $model?->model_name ?? 'Universal Fit' }}
                                        @if($model?->series)
                                            <small class="text-muted d-block">{{ $model->series }}</small>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <div class="trim-box">
                                        <i class="fas fa-microchip mr-2 text-muted small"></i>
                                        {{ $variant?->name ?? 'Standard' }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    @if($model?->production_start_year)
                                        <div class="year-range">
                                            <span class="year-tag">{{ $model->production_start_year }}</span>
                                            <span class="year-divider"></span>
                                            <span class="year-tag">{{ $model->production_end_year ?? 'Now' }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted small">Universal</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm font-weight-bold">
                                        <i class="fas fa-search-plus mr-1"></i> See All Parts
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="mt-3 text-muted small px-2">
                <i class="fas fa-info-circle mr-1"></i> Click "See All Parts" to view all components compatible with this specific vehicle configuration.
            </p>
        </div>
    </div>
    @endif

    {{-- 6. VERIFIED PURCHASE CUSTOMER REVIEWS (PATH A) --}}
    <div class="row mt-5 mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm tech-card bg-white p-4" style="border-radius: 16px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="shop-avatar mr-3 bg-light text-primary">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">Customer Reviews</h4>
                </div>

                {{-- Status Alerts --}}
                @if($errors->has('message'))
                    <div class="alert alert-danger rounded-3 small border-0 shadow-sm">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first('message') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success rounded-3 small border-0 shadow-sm">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- Write Review Interface Block --}}
                @auth
                    <div class="border-bottom pb-4 mb-4">
                        <form action="{{ route('reviews.store.web') }}" method="POST">
                            @csrf
                            <input type="hidden" name="reviewable_type" value="part">
                            <input type="hidden" name="reviewable_id" value="{{ $part->id }}">

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted d-block mb-1">Your Rating</label>
                                <div class="star-rating d-flex flex-row-reverse justify-content-end">
                                    <input type="radio" id="star5" name="rating" value="5" {{ old('rating') == 5 ? 'checked' : '' }} required />
                                    <label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star4" name="rating" value="4" {{ old('rating') == 4 ? 'checked' : '' }} />
                                    <label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star3" name="rating" value="3" {{ old('rating') == 3 ? 'checked' : '' }} />
                                    <label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star2" name="rating" value="2" {{ old('rating') == 2 ? 'checked' : '' }} />
                                    <label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star1" name="rating" value="1" {{ old('rating') == 1 ? 'checked' : '' }} />
                                    <label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="comment" class="form-label small fw-bold text-muted">Review Comments</label>
                                <textarea class="form-control rounded-3 border-light-subtle" id="comment" name="comment" rows="3" placeholder="Share your experience with this spare part... (optional)">{{ old('comment') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold btn-sm shadow-sm">
                                Submit Review
                            </button>
                        </form>
                    </div>
                @else
                    <div class="alert alert-light border rounded-3 p-4 text-center mb-4 bg-light">
                        <p class="text-muted small mb-2">Only registered customers who purchased this item can leave a review.</p>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                            Log In to Review
                        </a>
                    </div>
                @endauth

                {{-- Approved Reviews Display List Loop --}}
                <div class="review-list">
                    @forelse($part->reviews()->where('status', 'approved')->latest()->get() as $review)
                        <div class="d-flex mb-3 pb-3 border-bottom align-items-start">
                            <div class="bg-light text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 42px; height: 42px; min-width: 42px; font-size: 0.9rem;">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="d-flex align-items-center mb-1">
                                    <h6 class="fw-bold mb-0 me-2 text-dark small">{{ $review->user->name }}</h6>
                                    <div class="text-warning small" style="font-size: 0.75rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-muted small mb-1">{{ $review->comment ?? 'No written comment left.' }}</p>
                                <small class="text-muted-50 text-uppercase tracking-wider fw-bold" style="font-size: 0.65rem;">
                                    <i class="fas fa-check-circle text-success mr-1"></i> Verified Purchase &bull; {{ $review->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-muted small mb-0">No verified user evaluations left for this component yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection