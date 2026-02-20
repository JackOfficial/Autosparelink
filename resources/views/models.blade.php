@extends('layouts.app')

@php
    $brandName = $brand->brand_name ?? 'Vehicle';
    $faqs = [
        ['question' => "How do I order $brandName parts online?", 'answer' => "Select your model, then the specific variant to view the parts catalog."],
        ['question' => "Are these genuine $brandName parts?", 'answer' => "Yes, all catalog parts for $brandName are verified genuine components."],
        ['question' => "How long is shipping?", 'answer' => "Standard shipping for $brandName parts takes 3-5 business days."]
    ];
@endphp

@section('style')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    /* Global Refinements */
    body { 
        background-color: #fbfbfd !important; 
        font-family: 'Inter', -apple-system, sans-serif; 
        color: #1d1d1f;
    }

    /* Elegant Hero */
    .hero-container {
        background: #000;
        border-radius: 24px;
        padding: 4rem 2rem;
        margin-bottom: 4rem;
        color: #fff;
        text-align: center;
    }
    
    .hero-container h1 { font-size: 2.8rem; font-weight: 700; letter-spacing: -0.02em; }
    
    .search-glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 100px;
        padding: 8px 12px;
        max-width: 600px;
        margin: 2rem auto 0;
        display: flex;
    }
    
    .search-glass input {
        background: transparent;
        border: none;
        color: white;
        padding-left: 20px;
        font-size: 1.1rem;
    }
    
    .search-glass input::placeholder { color: rgba(255,255,255,0.5); }
    .search-glass input:focus { outline: none; box-shadow: none; }

    .btn-identify {
        background: #fff;
        color: #000;
        border-radius: 100px;
        padding: 10px 25px;
        font-weight: 600;
        border: none;
        transition: 0.3s;
    }
    .btn-identify:hover { background: #0071e3; color: #fff; }

    /* Model Cards */
    .model-card-minimal {
        background: #fff;
        border: 1px solid #f2f2f2;
        border-radius: 20px;
        padding: 30px;
        height: 100%;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .model-card-minimal:hover {
        border-color: transparent;
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        transform: translateY(-8px);
    }

    .model-title { font-size: 1.4rem; font-weight: 700; color: #1d1d1f; margin-bottom: 4px; }
    .model-subtitle { font-size: 0.9rem; color: #86868b; font-weight: 500; }

    /* Variant Slide-down */
    .variant-pill {
        display: block;
        padding: 12px 16px;
        margin-top: 8px;
        background: #f5f5f7;
        border-radius: 12px;
        color: #1d1d1f !important;
        font-weight: 500;
        text-decoration: none !important;
        transition: background 0.2s;
    }
    .variant-pill:hover { background: #e8e8ed; }

    /* Filter Input */
    .filter-box {
        border: none;
        background: #f5f5f7;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 500;
    }

    /* FAQ Refinement */
    .faq-item { border-bottom: 1px solid #f2f2f2; padding: 20px 0; }
    .faq-question { font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }

    [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')
<div class="container py-4" x-data="{ modelSearch: '', activeModel: null }">
    
    <div class="hero-container shadow-2xl">
        <h1>{{ $brandName }} <span style="font-weight: 300; opacity: 0.7;">Technical Catalog</span></h1>
        <p class="mt-2" style="font-size: 1.1rem; color: #a1a1a6;">Search by VIN or browse the model range below.</p>
        
        <form class="search-glass" method="GET" action="{{ route('brand.models', $brand?->id) }}">
            <input type="text" name="query" class="form-control" placeholder="Enter 17-digit VIN..." required>
            <button class="btn-identify">Identify</button>
        </form>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="font-weight-bold mb-0" style="letter-spacing: -0.01em;">Models</h2>
        </div>
        <div class="col-md-6 text-md-right mt-3 mt-md-0">
            <input type="text" x-model="modelSearch" class="filter-box w-75" placeholder="Search for a model...">
        </div>
    </div>

    <div class="row">
        @forelse($models as $model)
            <div class="col-lg-4 col-md-6 mb-4" 
                 x-show="modelSearch === '' || '{{ strtolower($model->model_name) }}'.includes(modelSearch.toLowerCase())">
                
                <div class="model-card-minimal" @click="activeModel = (activeModel === {{ $model->id }} ? null : {{ $model->id }})">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="model-title">{{ $model->model_name }}</div>
                            <div class="model-subtitle">
                                {{ $model->production_start_year ?? 'Series' }} — {{ $model->production_end_year ?? 'Present' }}
                            </div>
                        </div>
                        <i class="fas fa-chevron-right mt-2 text-muted transition" :class="activeModel === {{ $model->id }} ? 'rotate-90' : ''"></i>
                    </div>

                    <div x-show="activeModel === {{ $model->id }}" x-cloak @click.stop class="mt-4 pt-2 border-top">
                        @if($model->variants->count())
                            <p class="small font-weight-bold text-muted text-uppercase mb-2">Select Variant</p>
                            @foreach($model->variants as $variant)
                                <a href="{{ route('specifications.show', ['type' => 'variant', 'id' => $variant->id]) }}" class="variant-pill d-flex justify-content-between align-items-center">
                                    <span>{{ $variant->name }}</span>
                                    <i class="fas fa-arrow-right opacity-25 small"></i>
                                </a>
                            @endforeach
                        @else
                            <a href="{{ route('specifications.show', ['type' => 'model', 'id' => $model->id]) }}" class="btn btn-dark btn-block rounded-pill">View Catalog</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No models found in this category.</p>
            </div>
        @endforelse
    </div>

    <div class="row mt-5 pt-5">
        <div class="col-lg-10 mx-auto">
            <div class="row">
                <div class="col-md-5 mb-5">
                    <h3 class="font-weight-bold mb-4">About {{ $brandName }}</h3>
                    <p class="text-muted lead" style="font-size: 1rem; line-height: 1.8;">
                        {{ $brand?->description ?? "Comprehensive technical data and parts diagrams for all $brandName series vehicles." }}
                    </p>
                </div>
                <div class="col-md-6 offset-md-1">
                    <h3 class="font-weight-bold mb-4">Support</h3>
                    @foreach($faqs as $faq)
                        <div class="faq-item">
                            <div class="faq-question">
                                {{ $faq['question'] }}
                                <i class="fas fa-plus small text-muted"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection