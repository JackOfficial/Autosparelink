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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { background-color: #ffffff; font-family: 'Inter', sans-serif; color: #1a1a1a; }
    
    /* 1. Ultra-Modern Hero */
    .hero-showcase {
        padding: 100px 0 60px;
        background: radial-gradient(circle at top right, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 1px solid #f0f0f0;
        position: relative;
    }

    .brand-logo-main {
        max-height: 100px;
        width: auto;
        margin-bottom: 30px;
        filter: grayscale(1);
        transition: 0.5s;
    }
    .brand-logo-main:hover { filter: grayscale(0); }

    /* 2. Editorial Description */
    .brand-intro {
        max-width: 800px;
        margin: 0 auto 80px;
        text-align: center;
    }
    .brand-description {
        font-size: 1.25rem;
        color: #636366;
        line-height: 1.6;
        font-weight: 300;
    }

    /* 3. Compact Model Cards */
    .compact-card {
        background: #fff;
        border: 1px solid #efefef;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        margin-bottom: 15px;
    }

    .compact-card:hover {
        border-color: #000;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .model-name { font-weight: 700; font-size: 1.1rem; margin-bottom: 2px; }
    .model-years { font-size: 0.8rem; color: #86868b; text-transform: uppercase; letter-spacing: 0.5px; }

    /* 4. Variant Slide-out */
    .variant-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px dashed #eee;
    }

    .variant-btn {
        background: #f5f5f7;
        padding: 10px 15px;
        border-radius: 8px;
        color: #1d1d1f !important;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .variant-btn:hover { background: #000; color: #fff !important; }

    /* 5. Clean Collapsible FAQ */
    .faq-container { background: #fbfbfd; border-radius: 30px; padding: 60px 0; margin-top: 100px; }
    .faq-item { border-bottom: 1px solid #e5e5e5; }
    .faq-btn {
        width: 100%;
        padding: 25px 0;
        background: none;
        border: none;
        display: flex;
        justify-content: space-between;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
    }

    .rotate-180 { transform: rotate(180deg); transition: 0.3s; }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="{ modelSearch: '', activeModel: null, activeFaq: null }">
    
    <section class="hero-showcase">
        <div class="container text-center">
            @if($brand->brand_logo)
                <img src="{{ asset('storage/' . $brand->brand_logo) }}" class="brand-logo-main" alt="{{ $brandName }}">
            @endif
            <h1 class="display-4 font-weight-bold mb-4">{{ $brandName }}</h1>
            
            <form action="{{ route('brand.models', $brand?->id) }}" class="mx-auto" style="max-width: 600px;">
                <div class="position-relative">
                    <i class="fa-solid fa-magnifying-glass position-absolute" style="left: 20px; top: 20px; color: #888;"></i>
                    <input type="text" name="query" class="form-control shadow-none" 
                           style="height: 60px; border-radius: 30px; padding-left: 55px; border: 1px solid #e0e0e0;" 
                           placeholder="Search by VIN or Frame Number...">
                </div>
            </form>
        </div>
    </section>

    <div class="container mt-5">
        @if($brand->description)
        <div class="brand-intro">
            <p class="brand-description">{{ $brand->description }}</p>
        </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="font-weight-bold h3">Select Model Series</h2>
            <div class="position-relative">
                <input type="text" x-model="modelSearch" class="form-control form-control-sm border-0 bg-light" 
                       style="border-radius: 20px; padding: 10px 20px; min-width: 250px;" placeholder="Filter models...">
            </div>
        </div>

        <div class="row">
            @forelse($models as $model)
                @php $hasVariants = $model->variants->count() > 0; @endphp
                <div class="col-lg-4 col-md-6" x-show="modelSearch === '' || '{{ strtolower($model->model_name) }}'.includes(modelSearch.toLowerCase())">
                    <div class="compact-card" @click="{{ $hasVariants ? "activeModel = (activeModel === $model->id ? null : $model->id)" : "window.location.href='".route('specifications.show', ['type' => 'model', 'id' => $model->id])."'" }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="model-name">{{ $model->model_name }}</div>
                                @if($model->production_start_year)
                                    <div class="model-years">{{ $model->production_start_year }} — {{ $model->production_end_year ?? 'Present' }}</div>
                                @endif
                            </div>
                            
                            {{-- Collapse/Link Icon on the right end --}}
                            @if($hasVariants)
                                <i class="fa-solid fa-angle-down text-muted" :class="activeModel === {{ $model->id }} ? 'rotate-180' : ''"></i>
                            @else
                                <i class="fa-solid fa-arrow-right-long text-muted small"></i>
                            @endif
                        </div>

                        {{-- Expanded Variants --}}
                        @if($hasVariants)
                            <div x-show="activeModel === {{ $model->id }}" x-cloak x-collapse @click.stop>
                                <div class="variant-list">
                                    @foreach($model->variants as $variant)
                                        <a href="{{ route('specifications.show', ['type' => 'variant', 'id' => $variant->id]) }}" class="variant-btn">
                                            {{ $variant->name }}
                                            <i class="fa-solid fa-chevron-right fa-xs opacity-50"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted italic">No results match your filter.</p>
                </div>
            @endforelse
        </div>
    </div>

    <section class="faq-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h3 class="font-weight-bold mb-5">Frequently Asked Questions</h3>
                    @foreach($faqs as $index => $faq)
                        <div class="faq-item">
                            <button class="faq-btn" @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})">
                                <span>{{ $faq['question'] }}</span>
                                <i class="fa-solid fa-plus transition" :class="activeFaq === {{ $index }} ? 'rotate-45' : ''"></i>
                            </button>
                            <div x-show="activeFaq === {{ $index }}" x-cloak x-collapse>
                                <div class="pb-4 text-muted">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection