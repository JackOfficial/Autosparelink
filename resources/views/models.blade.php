@extends('layouts.app')

@php
    $brandName = $brand->brand_name ?? 'Vehicle';
    $faqs = [
        ['question' => "How do I ensure part compatibility?", 'answer' => "The most accurate way is using the VIN search above. Our catalog matches specific production dates and region codes for $brandName."],
        ['question' => "Are these official technical diagrams?", 'answer' => "Yes, the exploded views and part numbers are sourced from official technical databases."],
        ['question' => "What if my model isn't listed?", 'answer' => "Some classic or niche regional models may require a manual VIN identification. Contact support if the series is missing."]
    ];
@endphp

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { background-color: #fcfcfd !important; font-family: 'Inter', sans-serif; color: #1a1a1a; }
    
    /* Luxury Brand Header */
    .brand-hero {
        background: #ffffff;
        border-radius: 40px;
        padding: 80px 60px;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
        border: 1px solid #f0f0f0;
        box-shadow: 0 10px 50px rgba(0,0,0,0.02);
    }

    .brand-logo-hero { height: 90px; width: auto; margin-bottom: 30px; position: relative; z-index: 2; }

    .hero-watermark {
        position: absolute;
        right: -5%;
        top: 10%;
        height: 100%;
        opacity: 0.03;
        pointer-events: none;
    }

    /* Description Section - Editorial Style */
    .description-section { margin-bottom: 80px; }
    .description-text { 
        font-size: 1.15rem; 
        line-height: 1.8; 
        color: #4a4a4f; 
        font-weight: 400;
        border-left: 3px solid #000;
        padding-left: 30px;
    }

    /* Model Cards */
    .model-card {
        background: #fff;
        border-radius: 24px;
        padding: 35px;
        height: 100%;
        border: 1px solid #f0f0f0;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
    }

    .model-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.06);
        border-color: #e2e2e2;
    }

    .variant-link {
        display: flex;
        justify-content: space-between;
        padding: 15px 18px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-top: 10px;
        color: #1a1a1a !important;
        text-decoration: none !important;
        font-weight: 500;
        transition: 0.2s;
    }
    .variant-link:hover { background: #000; color: #fff !important; }

    /* Modern Collapsible FAQ */
    .faq-wrapper { max-width: 800px; margin: 0 auto; }
    .faq-item {
        background: #fff;
        border-radius: 16px;
        margin-bottom: 12px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }
    .faq-trigger {
        width: 100%;
        padding: 22px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: none;
        border: none;
        font-weight: 600;
        font-size: 1.05rem;
        text-align: left;
        cursor: pointer;
    }

    .rotate-180 { transform: rotate(180deg); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="container py-5" x-data="{ modelSearch: '', activeModel: null, activeFaq: null }">
    
    <div class="brand-hero text-center text-lg-left">
        @if($brand->brand_logo)
            <img src="{{ asset('storage/' . $brand->brand_logo) }}" class="hero-watermark d-none d-lg-block">
        @endif
        
        <div class="row align-items-center">
            <div class="col-lg-7">
                @if($brand->brand_logo)
                    <img src="{{ asset('storage/' . $brand->brand_logo) }}" class="brand-logo-hero" alt="{{ $brandName }}">
                @endif
                <h1 class="display-3 font-weight-bold" style="letter-spacing: -3px;">{{ $brandName }}</h1>
                <p class="lead text-muted mb-5">Access the complete technical parts assembly for your vehicle.</p>
                
                <form method="GET" action="{{ route('brand.models', $brand?->id) }}" class="d-inline-block w-100">
                    <div class="input-group bg-light p-2 shadow-sm" style="border-radius: 20px; border: 1px solid #eee;">
                        <input type="text" name="query" class="form-control border-0 bg-transparent px-4" 
                               style="height: 50px;" placeholder="Identify by VIN...">
                        <div class="input-group-append">
                            <button class="btn btn-dark px-5 font-weight-bold" style="border-radius: 15px;">Identify</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($brand->description)
    <div class="description-section row align-items-center px-3">
        <div class="col-lg-8">
            <h6 class="text-uppercase font-weight-bold text-primary mb-3" style="letter-spacing: 2px; font-size: 0.8rem;">Brand Legacy</h6>
            <div class="description-text">
                {{ $brand->description }}
            </div>
        </div>
    </div>
    @endif

    <div class="row mb-5 mt-5">
        <div class="col-12 d-flex justify-content-between align-items-end mb-4 px-4">
            <h2 class="font-weight-bold m-0">Available Series</h2>
            <input type="text" x-model="modelSearch" class="form-control border-0 shadow-sm bg-white" 
                   style="max-width: 250px; border-radius: 12px;" placeholder="Filter models...">
        </div>

        @forelse($models as $model)
            @php $hasVariants = $model->variants->count() > 0; @endphp
            
            <div class="col-lg-4 col-md-6 mb-4" 
                 x-show="modelSearch === '' || '{{ strtolower($model->model_name) }}'.includes(modelSearch.toLowerCase())">
                
                <div class="model-card shadow-sm" 
                     @click="{{ $hasVariants ? "activeModel = (activeModel === $model->id ? null : $model->id)" : "window.location.href='".route('specifications.show', ['type' => 'model', 'id' => $model->id])."'" }}">
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        @if($hasVariants)
                            <i class="fas fa-chevron-down text-muted transition" :class="activeModel === {{ $model->id }} ? 'rotate-180' : ''"></i>
                        @else
                            <i class="fas fa-arrow-right text-muted small"></i>
                        @endif
                    </div>
                    
                    <h3 class="font-weight-bold h4">{{ $model->model_name }}</h3>

                    @if($hasVariants)
                        <div x-show="activeModel === {{ $model->id }}" x-cloak x-collapse class="mt-4 pt-3 border-top" @click.stop>
                            @foreach($model->variants as $variant)
                                <a href="{{ route('specifications.show', ['type' => 'variant', 'id' => $variant->id]) }}" class="variant-link">
                                    {{ $variant->name }}
                                    <i class="fas fa-plus small opacity-50"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No model series found for this selection.</p>
            </div>
        @endforelse
    </div>

    <div class="faq-wrapper py-5">
        <h3 class="text-center font-weight-bold mb-5">Frequently Asked Questions</h3>
        
        @foreach($faqs as $index => $faq)
            <div class="faq-item shadow-sm">
                <button class="faq-trigger" @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})">
                    <span>{{ $faq['question'] }}</span>
                    <i class="fas fa-chevron-down transition" :class="activeFaq === {{ $index }} ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="activeFaq === {{ $index }}" x-cloak x-collapse>
                    <div class="px-4 pb-4 text-muted">
                        {{ $faq['answer'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection