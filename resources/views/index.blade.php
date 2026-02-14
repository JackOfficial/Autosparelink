@extends('layouts.app')
@section('content')

<!-- =================== STYLES (HERO + GLOBAL IMPROVEMENTS) =================== -->
<style>
    :root{
        --primary: #0d6efd;
        --muted: #6c757d;
        --card-radius: 10px;
    }

    /* HERO */
    .hero-banner { 
        height: 500px; 
        background: url('{{ asset('frontend/img/part.png') }}') center/cover no-repeat; 
        position: relative; 
        overflow: hidden; 
    }
    .hero-overlay { 
        background: linear-gradient(90deg, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.25) 60%); 
        position: absolute; inset: 0; 
    }
    .hero-content { 
        position: relative; 
        z-index: 3; 
        max-width: 760px; 
    }
    .hero-title { 
        font-size: 2.6rem; 
        line-height: 1.05; 
        letter-spacing: -0.5px; 
    }
    .hero-sub { 
        font-size: 1.05rem; 
        color: rgba(255,255,255,0.9); 
    }

    /* SEARCH */
    .search-wrapper { 
        background: #fff; 
        border-radius: 999px; 
        padding: 6px; 
        display:flex; 
        gap:10px; 
        align-items:center; 
        box-shadow: 0 12px 30px rgba(2,6,23,0.28); 
    }
    .search-wrapper input { 
        border: none; 
        border-radius: 999px; 
        padding-left: 18px; 
        height:56px; 
        font-size:16px; 
        width:100%; 
    }
    .search-wrapper input:focus { 
        outline:none; 
        box-shadow:none; 
    }
    .search-wrapper .btn { 
        height: 46px; 
        padding: 0 28px; 
        border-radius: 999px; 
    }

    /* POPULAR SEARCHES / BRANDS */
    .hero-popular { 
        margin-top: 12px; 
        font-size: 0.95rem; 
        color: rgba(255,255,255,0.85); 
    }
    .hero-brands { 
        margin-top: 18px; 
        display:flex; 
        gap:12px; 
        align-items:center; 
        flex-wrap:wrap; 
    }
    .brand-pill { 
        background: rgba(255,255,255,0.08); 
        border-radius: 999px; 
        padding:6px 10px; 
        display:flex; 
        gap:8px; 
        align-items:center; 
        color: #fff; 
        font-weight:600; 
        font-size:0.9rem; 
    }
    .brand-pill img { 
        width:28px; 
        height:20px; 
        object-fit:contain; 
        filter: brightness(1.1); 
    }

    /* SECTION TITLES */
    .section-header { 
        display:flex; 
        justify-content:space-between; 
        align-items:center; 
        margin-bottom: 18px; 
    }
    .section-header h2 { 
        font-size:1.125rem; 
        font-weight:700; 
        margin:0; 
    }

    /* FEATURE CARDS */
    .feature-card { 
        transition: transform .22s ease, box-shadow .22s ease; 
        border-radius: var(--card-radius); 
    }
    .feature-card:hover { 
        transform: translateY(-6px); 
        box-shadow: 0 18px 40px rgba(2,6,23,0.08); 
    }
    .feature-icon { 
        width:64px; 
        height:64px; 
        border-radius:50%; 
        display:flex; 
        align-items:center; 
        justify-content:center; 
        background: rgba(13,110,253,0.08); 
        color: var(--primary); 
    }

    /* CATEGORIES / BRAND CARDS */
    .cat-item { 
        background: #fff; 
        border-radius: 10px; 
        padding:12px; 
        box-shadow: 0 8px 20px rgba(2,6,23,0.04); 
        transition: transform .18s ease, box-shadow .18s ease; 
    }
    .cat-item:hover { 
        transform: translateY(-6px); 
        box-shadow: 0 22px 50px rgba(2,6,23,0.06); 
    }
    .cat-item img { 
        width:80px; 
        height:80px; 
        object-fit:contain; 
    }

    /* VENDOR CAROUSEL */
    .vendor-carousel .bg-light{ 
        display:flex; 
        align-items:center; 
        justify-content:center; 
        border-radius:8px; 
        padding:18px; 
        min-height:80px; 
    }
    .vendor-carousel img{ 
        max-width:140px; 
        max-height:60px; 
        object-fit:contain; 
    }

    /* RESPONSIVE */
    @media (max-width: 991px){ 
        .hero-banner{ height: 420px; } 
        .hero-title{ font-size:1.6rem; } 
    }
    @media (max-width: 576px){ 
        .hero-banner{ height:360px; } 
        .hero-content{ padding:0 16px; } 
        .brand-pill img{ display:none; } 
    }
</style>

<!-- =================== HERO =================== -->
<div class="hero-banner d-flex align-items-center">
    <div class="hero-overlay"></div>
    <div class="container-fluid">
        <div class="hero-content text-white px-4">
            <h1 class="hero-title text-white fw-bold mb-2 animate__animated animate__fadeInDown">Find Genuine Spare Parts.</h1>
            <p class="hero-sub mb-3 animate__animated animate__fadeInUp">Use your VIN or Frame number to instantly find parts that fit your exact vehicle model.</p>

            <!-- VIN Search Box -->
            <form action="/vin-search" method="GET" class="animate__animated animate__fadeInUp" role="search" aria-label="Search by VIN">
                <div class="search-wrapper">
                    <input aria-label="Search by VIN" type="text" name="vin" placeholder="Enter VIN / Frame Number (e.g. JTDBE32K220123456)" required>
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
                 @error('vin')<div class="text-danger text-center">{{ $message }}</div>@enderror
                <div class="d-flex align-items-center justify-content-between hero-popular mt-2">
                    <div>
                        <small class="me-3">Popular searches:</small>
                          @foreach($brands->take(4) as $brand)
                    <a href="{{ url('models/'.$brand->id) }}" class="text-white fw-bold me-2">
                    {{ $brand->brand_name }}
                    </a>
                    @endforeach
                        <a class="text-white fw-bold" href="/brands">Advanced search</a>
                    </div>
                </div>

                <div class="hero-brands mt-2">
                    @foreach($brands->take(5) as $brand)
                    <a href="{{ url('models/'.$brand->id) }}">
                        <div class="brand-pill">
                             <img src="{{ asset('storage/' . $brand->brand_logo) }}" alt="{{ $brand->brand_name }} logo">
                            {{ strtoupper($brand->brand_name) }}
                        </div>
                         </a>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =================== FEATURES =================== -->
<div class="container-fluid py-4 bg-light">
    <div class="row px-xl-5 gy-4">
        <div class="col-lg-3 col-md-6">
            <div class="feature-card d-flex align-items-center p-3">
                <div class="feature-icon me-3"><i class="fa fa-check fa-lg"></i></div>
                <div><h6 class="fw-bold mb-1">Genuine OEM Parts</h6><small class="text-muted">Certified & quality-assured</small></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="feature-card d-flex align-items-center p-3">
                <div class="feature-icon me-3"><i class="fa fa-shipping-fast fa-lg"></i></div>
                <div><h6 class="fw-bold mb-1">Fast & Secure Shipping</h6><small class="text-muted">Worldwide delivery & tracking</small></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="feature-card d-flex align-items-center p-3">
                <div class="feature-icon me-3"><i class="fas fa-cogs fa-lg"></i></div>
                <div><h6 class="fw-bold mb-1">{{ number_format($partsCounter) }} Parts In Stock</h6><small class="text-muted">Search across our catalog</small></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="feature-card d-flex align-items-center p-3">
                <div class="feature-icon me-3"><i class="fa fa-headset fa-lg"></i></div>
                <div><h6 class="fw-bold mb-1">Expert Support</h6><small class="text-muted">Help with fitment & returns</small></div>
            </div>
        </div>
    </div>
</div>

<!-- =================== CATEGORIES / BRANDS =================== -->
<div class="container-fluid pt-4">
    <div class="section-header px-xl-5">
        <h2>Genuine Parts Online Catalogs</h2>
        <a href="/brands" class="text-decoration-none">View all brands →</a>
    </div>
    <div class="row px-xl-5 pb-3">
        @forelse ($brands as $brand)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                <a href="{{ url('models/'.$brand->id) }}" class="text-decoration-none">
                    <div class="cat-item d-flex align-items-center gap-3 p-3">
                        <div class="overflow-hidden" style="width:85px; height:85px;">
                            <img class="img-fluid" src="{{ asset('storage/' . $brand->brand_logo) }}" alt="{{ $brand->brand_name }}" loading="lazy">
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1 text-dark">{{ $brand->brand_name }}</h6>
                            <small class="text-muted">{{ $brand->parts_count ?? 0 }} Products</small>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-3">No brands available.</div>
        @endforelse
    </div>
</div>

<!-- =================== FEATURED PRODUCTS =================== -->
<div class="container-fluid pt-4 pb-3">
    <div class="section-header px-xl-5">
        <h2>Featured Spare Parts</h2>
        <a href="/spare-parts" class="text-decoration-none">See all featured →</a>
    </div>
    <div class="row px-xl-5">
        @forelse ($parts as $part)
            @livewire('part-component', ['part' => $part], key($part->id))
        @empty
            <div class="col-12 text-center py-3">No spare parts found.</div>
        @endforelse
    </div>
</div>

<!-- =================== RECENT PRODUCTS =================== -->
<div class="container-fluid pt-4 pb-3">
    <div class="section-header px-xl-5">
        <h2>Recent Spare Parts</h2>
        <a href="/spare-parts" class="text-decoration-none">See all recent →</a>
    </div>
    <div class="row px-xl-5">
        @forelse ($recent_parts as $recent_part)
            @livewire('part-component', ['part' => $recent_part], key($recent_part->id))
        @empty
            <div class="col-12 text-center py-3">No recent spare parts available.</div>
        @endforelse
    </div>
</div>

@endsection
