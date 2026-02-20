@extends('layouts.app')

@php
    $brandName = $brand->brand_name ?? 'Vehicle';
    $faqs = [
        ['question' => "Genuine Parts Guarantee", 'answer' => "Every component listed in the $brandName catalog is verified for fitment and quality."],
        ['question' => "Technical Support", 'answer' => "Our diagrams are sourced directly from technical specifications to ensure 100% accuracy."],
        ['question' => "Global Availability", 'answer' => "We provide part numbers that are recognized by $brandName dealers worldwide."]
    ];
@endphp

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { background-color: #f8f9fa !important; font-family: 'Inter', sans-serif; color: #1a1a1a; }
    
    /* Brand Showcase Hero */
    .brand-hero {
        background: #ffffff;
        border-radius: 30px;
        padding: 60px;
        margin-bottom: 50px;
        position: relative;
        overflow: hidden;
        border: 1px solid #eaeaea;
        box-shadow: 0 4px 24px rgba(0,0,0,0.03);
    }

    .brand-logo-main {
        height: 80px;
        width: auto;
        margin-bottom: 25px;
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
    }

    .hero-watermark {
        position: absolute;
        right: -5%;
        top: 50%;
        transform: translateY(-50%);
        height: 120%;
        opacity: 0.04;
        pointer-events: none;
    }

    /* Modern Search Input */
    .search-container {
        max-width: 550px;
        position: relative;
        z-index: 2;
    }

    .search-input-premium {
        height: 60px;
        border-radius: 15px;
        border: 2px solid #f0f0f0;
        padding-left: 25px;
        font-size: 1.05rem;
        transition: all 0.3s;
        background: #fdfdfd;
    }

    .search-input-premium:focus {
        border-color: #000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        outline: none;
    }

    /* Elegant Grid */
    .model-card {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        height: 100%;
        border: 1px solid transparent;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
    }

    .model-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.1);
        border-color: #eee;
    }

    .model-badge {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #888;
        margin-bottom: 8px;
        display: block;
    }

    .variant-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f5f5f5;
        color: #333 !important;
        font-weight: 500;
        text-decoration: none !important;
    }

    .variant-item:last-child { border-bottom: none; }
    .variant-item:hover { color: #007bff !important; }

    /* Non-Collapsible FAQ */
    .faq-section { margin-top: 100px; padding-bottom: 80px; }
    .faq-card {
        padding: 30px;
        border-radius: 20px;
        background: transparent;
    }
    .faq-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 15px; color: #000; }
    .faq-text { color: #666; line-height: 1.6; font-size: 0.95rem; }

    .rotate-180 { transform: rotate(180deg); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="container-fluid py-5" x-data="{ modelSearch: '', activeModel: null }">
    
    <div class="brand-hero">
        @if($brand->brand_logo)
            <img src="{{ asset('storage/' . $brand->brand_logo) }}" class="hero-watermark d-none d-lg-block">
            <img src="{{ asset('storage/' . $brand->brand_logo) }}" class="brand-logo-main" alt="{{ $brandName }}">
        @endif
        
        <div class="row">
            <div class="col-lg-7">
                <h1 class="font-weight-bold display-4 mb-3" style="letter-spacing: -2px;">{{ $brandName }}</h1>
                <p class="lead text-muted mb-4">Select your series to explore genuine technical components and parts diagrams.</p>
                
                <form class="search-container" method="GET" action="{{ route('brand.models', $brand?->id) }}">
                    <div class="input-group">
                        <input type="text" name="query" class="form-control search-input-premium shadow-none" placeholder="Enter your 17-digit VIN...">
                        <div class="input-group-append">
                            <button class="btn btn-dark px-4 ml-2 rounded-lg" style="border-radius: 12px;">Identify</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 px-3">
        <h3 class="font-weight-bold m-0">Model Series</h3>
        <input type="text" x-model="modelSearch" class="form-control border-0 bg-white shadow-sm mt-3 mt-md-0" 
               style="max-width: 300px; border-radius: 10px;" placeholder="Search model...">
    </div>

    <div class="row">
        @forelse($models as $model)
            <div class="col-lg-4 col-md-6 mb-4" 
                 x-show="modelSearch === '' || '{{ strtolower($model->model_name) }}'.includes(modelSearch.toLowerCase())">
                
                <div class="model-card shadow-sm" @click="activeModel = (activeModel === {{ $model->id }} ? null : {{ $model->id }})">
                    <span class="model-badge">{{ $model->production_start_year ?? 'Production' }} — {{ $model->production_end_year ?? 'Present' }}</span>
                    <h4 class="font-weight-bold mb-3">{{ $model->model_name }}</h4>
                    
                    <div class="d-flex align-items-center text-primary font-weight-bold" style="cursor: pointer;">
                        <span>{{ $model->variants->count() }} Variants Found</span>
                        <i class="fas fa-chevron-down ml-2 transition" :class="activeModel === {{ $model->id }} ? 'rotate-180' : ''"></i>
                    </div>

                    <div x-show="activeModel === {{ $model->id }}" x-cloak x-collapse class="mt-4 pt-3 border-top" @click.stop>
                        @foreach($model->variants as $variant)
                            <a href="{{ route('specifications.show', ['type' => 'variant', 'id' => $variant->id]) }}" class="variant-item">
                                {{ $variant->name }}
                                <i class="fas fa-chevron-right small opacity-25"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No models found for this brand.</p>
            </div>
        @endforelse
    </div>

    <div class="faq-section px-3">
        <div class="row">
            <div class="col-lg-4">
                <h2 class="font-weight-bold mb-4">Technical<br>Information</h2>
                <p class="text-muted">Quick reference guide for the {{ $brandName }} catalog system.</p>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    @foreach($faqs as $faq)
                        <div class="col-md-6 mb-4">
                            <div class="faq-card h-100">
                                <div class="faq-title">{{ $faq['question'] }}</div>
                                <div class="faq-text">{{ $faq['answer'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection