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
    
    /* Hero & Intro */
    .hero-showcase { padding: 80px 0 40px; background: #fff; border-bottom: 1px solid #f0f0f0; }
    .brand-logo-main { max-height: 80px; width: auto; margin-bottom: 25px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05)); }
    .brand-description { font-size: 1.15rem; color: #636366; line-height: 1.6; max-width: 750px; margin: 0 auto 50px; font-weight: 300; }

    /* Compact Model Cards */
    .compact-card {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 16px;
        padding: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        margin-bottom: 20px;
    }

    .compact-card:hover {
        border-color: #000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .card-icon {
        width: 40px;
        height: 40px;
        background: #f5f5f7;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #1d1d1f;
    }

    .model-name { font-weight: 700; font-size: 1.1rem; }
    .model-years { font-size: 0.8rem; color: #86868b; }

    /* Full-Width Variant List */
    .variant-container {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
    }

    .variant-full-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f9f9fb;
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 8px;
        color: #1d1d1f !important;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none !important;
        transition: 0.2s;
        width: 100%; /* Spans full length of card */
    }

    .variant-full-btn:hover {
        background: #000;
        color: #fff !important;
    }

    .variant-full-btn:last-child { margin-bottom: 0; }

    /* FAQ */
    .faq-item { border-bottom: 1px solid #eee; }
    .faq-btn {
        width: 100%;
        padding: 20px 0;
        background: none;
        border: none;
        display: flex;
        justify-content: space-between;
        font-weight: 600;
        cursor: pointer;
    }

    .rotate-180 { transform: rotate(180deg); transition: 0.3s; }
    .rotate-45 { transform: rotate(45deg); transition: 0.3s; }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="{ modelSearch: '', activeModel: null, activeFaq: null }">
    
    <section class="hero-showcase text-center">
        <div class="container">
            @if($brand->brand_logo)
                <img src="{{ asset('storage/' . $brand->brand_logo) }}" class="brand-logo-main" alt="{{ $brandName }}">
            @endif
            <h1 class="font-weight-bold mb-3">{{ $brandName }} Technical Catalog</h1>
            
            @if($brand->description)
                <p class="brand-description">{{ $brand->description }}</p>
            @endif

            <form action="{{ route('brand.models', $brand?->id) }}" class="mx-auto" style="max-width: 550px;">
                <div class="input-group bg-white shadow-sm border" style="border-radius: 30px; overflow: hidden; padding: 5px;">
                    <input type="text" name="query" class="form-control border-0 px-4" placeholder="Search by VIN...">
                    <div class="input-group-append">
                        <button class="btn btn-dark px-4" style="border-radius: 25px;">Identify</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <h3 class="font-weight-bold mb-0">Models</h3>
            <input type="text" x-model="modelSearch" class="form-control form-control-sm border-0 bg-light" 
                   style="border-radius: 10px; max-width: 200px;" placeholder="Filter models...">
        </div>

       <div class="row">
    @forelse($models as $model)
        @php 
            $hasVariants = $model->variants->count() > 0; 
            // Null-safe assignment: first() returns null if no variants exist
            $defaultVariant = $model->variants->first(); 
        @endphp

        <div class="col-lg-4 col-md-6" x-show="modelSearch === '' || '{{ strtolower($model->model_name) }}'.includes(modelSearch.toLowerCase())">
            
            {{-- Patch: Added logic to handle the case where $defaultVariant is null --}}
            <div class="compact-card shadow-sm" 
                 @click="{{ $hasVariants 
                    ? "activeModel = (activeModel === $model->id ? null : $model->id)" 
                    : ($defaultVariant 
                        ? "window.location.href='".route('variant.specifications', $defaultVariant->slug)."'" 
                        : "console.warn('No variants found for this model'); alert('Specifications coming soon!')") 
                 }}">
                
                <div class="d-flex align-items-center">
                    <div class="card-icon">
                        <i class="fa-solid fa-car-side"></i>
                    </div>
                    
                    <div class="flex-grow-1">
                        <div class="model-name">{{ $model->model_name }}</div>
                        @if($model->production_start_year)
                            <div class="model-years">{{ $model->production_start_year }} — {{ $model->production_end_year ?? 'Present' }}</div>
                        @endif
                    </div>

                    @if($hasVariants)
                        <i class="fa-solid fa-chevron-down text-muted small transition" :class="activeModel === {{ $model->id }} ? 'rotate-180' : ''"></i>
                    @else
                        <i class="fa-solid fa-arrow-right text-muted small"></i>
                    @endif
                </div>

                @if($hasVariants)
                    <div x-show="activeModel === {{ $model->id }}" x-cloak x-collapse @click.stop class="variant-container">
                        @foreach($model->variants as $variant)
                            {{-- Null-safe slug fallback just in case of DB corruption --}}
                            <a href="{{ route('variant.specifications', $variant->slug ?? 'default') }}" class="variant-full-btn">
                                <span>{{ $variant->name }}</span>
                                <i class="fa-solid fa-chevron-right fa-xs opacity-50"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">No models found for this brand.</p>
        </div>
    @endforelse
</div>

        <div class="row justify-content-center mt-5 pt-5">
            <div class="col-lg-8">
                <h4 class="font-weight-bold mb-4">Common Questions</h4>
                @foreach($faqs as $index => $faq)
                    <div class="faq-item">
                        <button class="faq-btn" @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})">
                            <span class="text-left pr-3">{{ $faq['question'] }}</span>
                            <i class="fa-solid fa-plus text-muted transition" :class="activeFaq === {{ $index }} ? 'rotate-45' : ''"></i>
                        </button>
                        <div x-show="activeFaq === {{ $index }}" x-cloak x-collapse>
                            <div class="pb-4 text-muted small">
                                {{ $faq['answer'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection