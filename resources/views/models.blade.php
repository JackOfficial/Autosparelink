@extends('layouts.app')

@php
    /**
     * Data Preparation
     */
    $brandName = $brand->brand_name ?? 'Vehicle';
    
    $faqs = [
        ['question' => "How do I order $brandName parts online?", 'answer' => "Select your model, then the specific variant to view the parts catalog. Add items to your cart and proceed to checkout."],
        ['question' => "Are these genuine $brandName parts?", 'answer' => "Yes, we specialize in sourcing genuine components and high-quality OEM parts for $brandName vehicles."],
        ['question' => "What if I can't find my VIN?", 'answer' => "If your VIN isn't recognized, you can manually browse by model series and production year using the grid below."],
    ];
@endphp

@section('style')
<style>
    :root { --brand-primary: #007bff; --bg-soft: #f8f9fa; }
    body { background: var(--bg-soft) !important; font-family: 'Inter', sans-serif; }
    
    /* Breadcrumb styling */
    .custom-breadcrumb { padding: 10px 0; font-size: 0.9rem; }
    .custom-breadcrumb a { text-decoration: none; color: var(--brand-primary); }

    /* Hero & Card Effects */
    .hero-search {
        background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
        color: white;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    .model-card {
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 20px;
        background: #fff;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .model-card:hover {
        border-color: var(--brand-primary);
        box-shadow: 0 10px 25px rgba(0,123,255,0.1);
        transform: translateY(-5px);
    }

    .variant-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none !important;
        color: #444;
        margin-bottom: 5px;
        background: #f8f9fb;
        transition: 0.2s;
    }
    .variant-link:hover { background: #eef4ff; color: var(--brand-primary); }

    .rotate-180 { transform: rotate(180deg); }
    .transition-icon { transition: transform 0.3s ease; }
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')
<div x-data="{ 
    modelSearch: '', 
    activeModel: null 
}">
    
    <div class="container-fluid px-xl-5">
        <nav class="custom-breadcrumb">
            <a href="{{ route('home') }}">Home</a> 
            <span class="mx-2 text-muted">/</span> 
            <span class="text-muted">{{ $brandName }}</span>
        </nav>
    </div>

    <div class="container-fluid px-xl-5 mt-2">
        <div class="hero-search p-5 shadow-lg mb-5">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-5 fw-bold mb-2">Find Genuine {{ $brandName }} Parts</h1>
                    <p class="opacity-75">Access the official technical catalog by entering your vehicle identification number.</p>
                    
                    <form class="mt-4" method="GET" action="{{ route('brand.models', $brand?->id) }}">
                        <div class="input-group input-group-lg shadow">
                            <input type="text" name="query" class="form-control border-0" 
                                   placeholder="Enter 17-digit VIN or Frame Number..." value="{{ request('query') }}">
                            <button class="btn btn-primary px-4">Identify Vehicle</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center">
                    <i class="fas fa-car-side fa-10x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-xl-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-4 gap-3">
            <div>
                <h2 class="fw-bold mb-0">Browse by Model</h2>
                <p class="text-muted">Showing {{ $models->count() }} model series for {{ $brandName }}</p>
            </div>
            <div class="col-md-4">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" x-model="modelSearch" class="form-control ps-5 rounded-pill shadow-sm" 
                           placeholder="Filter models (e.g. RAV4, Civic)...">
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($models as $model)
                <div class="col-xl-3 col-lg-4 col-md-6" 
                     x-show="modelSearch === '' || '{{ strtolower($model->model_name) }}'.includes(modelSearch.toLowerCase())"
                     x-transition:enter.duration.300ms>
                    
                    <div class="model-card shadow-sm">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $model->model_name }}</h5>
                                <span class="badge bg-light text-muted border">
                                    {{ $model->production_start_year ?? '?' }} - {{ $model->production_end_year ?? 'Present' }}
                                </span>
                            </div>
                            @if($brand && $brand->brand_logo)
                                <img src="{{ asset('storage/' . $brand->brand_logo) }}" width="30" class="opacity-50">
                            @endif
                        </div>

                        <hr class="my-3 opacity-50">

                        @if($model->variants->count())
                            <button @click="activeModel = (activeModel === {{ $model->id }} ? null : {{ $model->id }})" 
                                    class="btn btn-outline-primary btn-sm w-100 d-flex justify-content-between align-items-center">
                                <span>{{ $model->variants->count() }} Variants</span>
                                <i class="fas fa-chevron-down transition-icon" :class="activeModel === {{ $model->id }} ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="activeModel === {{ $model->id }}" 
                                 x-collapse x-cloak class="mt-3">
                                @foreach($model->variants as $variant)
                                    <a href="{{ route('specifications.show', ['type' => 'variant', 'id' => $variant->id]) }}" 
                                       class="variant-link">
                                        <div>
                                            <span class="fw-semibold">{{ $variant->name }}</span>
                                            <div class="small text-muted">{{ $variant->engine_type?->name ?? 'Standard Engine' }}</div>
                                        </div>
                                        <i class="fas fa-chevron-right fa-xs opacity-50"></i>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <a href="{{ route('specifications.show', ['type' => 'model', 'id' => $model->id]) }}" 
                               class="btn btn-primary btn-sm w-100">Open Catalog</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No models found for this brand.</h4>
                </div>
            @endforelse
        </div>
    </div>

    <div class="container-fluid px-xl-5 mt-5 pb-5">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100 border border-light">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-award text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-0">About {{ $brandName }}</h4>
                    </div>
                    <p class="text-muted" style="line-height: 1.7;">
                        {{ $brand?->description ?? "Explore our comprehensive catalog of genuine $brandName parts and accessories. We provide technical diagrams and OEM specifications to ensure you find the exact fit for your vehicle." }}
                    </p>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
                    <h4 class="fw-bold mb-4">Frequently Asked Questions</h4>
                    <div class="accordion accordion-flush" id="faqGrid">
                        @foreach($faqs as $key => $faq)
                            <div class="accordion-item border-0 mb-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button rounded-3 collapsed bg-light mb-2 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f{{ $key }}">
                                        <strong>{{ $faq['question'] }}</strong>
                                    </button>
                                </h2>
                                <div id="f{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#faqGrid">
                                    <div class="accordion-body text-muted px-2">{{ $faq['answer'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection