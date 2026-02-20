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
    body { background-color: #fbfbfb !important; font-family: 'Inter', sans-serif; color: #1a1a1a; }
    
    /* Elegant Hero */
    .brand-hero {
        background: #ffffff;
        border-radius: 30px;
        padding: 50px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }

    .brand-logo-hero { height: 60px; width: auto; margin-bottom: 20px; }

    /* Description Box */
    .description-card {
        background: #fff;
        border-radius: 20px;
        padding: 25px;
        border: 1px solid #f0f0f0;
        height: 100%;
    }

    /* Compact Model Cards */
    .model-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1px solid #eee;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .model-card:hover {
        border-color: #000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    }

    .variant-link {
        display: flex;
        justify-content: space-between;
        padding: 10px 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-top: 8px;
        color: #1a1a1a !important;
        text-decoration: none !important;
        font-size: 0.9rem;
        font-weight: 500;
    }
    .variant-link:hover { background: #000; color: #fff !important; }

    /* Collapsible FAQ */
    .faq-item {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 10px;
        border: 1px solid #eee;
    }
    .faq-trigger {
        width: 100%;
        padding: 18px 25px;
        display: flex;
        justify-content: space-between;
        background: none;
        border: none;
        font-weight: 600;
        cursor: pointer;
    }

    .rotate-180 { transform: rotate(180deg); transition: 0.3s; }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="container py-5" x-data="{ modelSearch: '', activeModel: null, activeFaq: null }">
    
    <div class="brand-hero shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-7">
                @if($brand->brand_logo)
                    <img src="{{ asset('storage/' . $brand->brand_logo) }}" class="brand-logo-hero" alt="{{ $brandName }}">
                @endif
                <h1 class="font-weight-bold mb-2">{{ $brandName }} <span class="text-muted font-weight-light">Catalog</span></h1>
                <p class="text-muted mb-4">Enter a VIN or browse by series to find parts diagrams.</p>
                
                <form method="GET" action="{{ route('brand.models', $brand?->id) }}">
                    <div class="input-group" style="max-width: 450px;">
                        <input type="text" name="query" class="form-control border shadow-none" 
                               style="height: 50px; border-radius: 12px 0 0 12px;" placeholder="Identify by VIN...">
                        <div class="input-group-append">
                            <button class="btn btn-dark px-4 font-weight-bold" style="border-radius: 0 12px 12px 0;">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-4 mb-4">
            <div class="description-card shadow-sm">
                <h6 class="text-uppercase font-weight-bold text-muted small mb-3"><i class="fas fa-info-circle mr-2"></i>About the Brand</h6>
                <p class="text-muted" style="line-height: 1.6; font-size: 0.95rem;">
                    {{ $brand->description ?? "Explore comprehensive technical data and exploded parts views for the $brandName lineup." }}
                </p>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="font-weight-bold m-0">Models</h4>
                <input type="text" x-model="modelSearch" class="form-control form-control-sm border-0 shadow-sm" 
                       style="max-width: 200px; border-radius: 8px;" placeholder="Filter list...">
            </div>

            <div class="row">
                @forelse($models as $model)
                    @php $hasVariants = $model->variants->count() > 0; @endphp
                    
                    <div class="col-md-6 mb-3" x-show="modelSearch === '' || '{{ strtolower($model->model_name) }}'.includes(modelSearch.toLowerCase())">
                        <div class="model-card shadow-sm" 
                             @click="{{ $hasVariants ? "activeModel = (activeModel === $model->id ? null : $model->id)" : "window.location.href='".route('specifications.show', ['type' => 'model', 'id' => $model->id])."'" }}">
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="font-weight-bold mb-0" style="font-size: 1.1rem;">{{ $model->model_name }}</h5>
                                    @if($model->production_start_year)
                                        <span class="text-muted small">{{ $model->production_start_year }} — {{ $model->production_end_year ?? 'Present' }}</span>
                                    @endif
                                </div>
                                
                                {{-- Icon on the right end --}}
                                @if($hasVariants)
                                    <i class="fas fa-chevron-down text-muted opacity-50 transition" :class="activeModel === {{ $model->id }} ? 'rotate-180' : ''"></i>
                                @else
                                    <i class="fas fa-arrow-right text-muted opacity-50 small"></i>
                                @endif
                            </div>

                            @if($hasVariants)
                                <div x-show="activeModel === {{ $model->id }}" x-cloak x-collapse class="mt-3 pt-3 border-top" @click.stop>
                                    @foreach($model->variants as $variant)
                                        <a href="{{ route('specifications.show', ['type' => 'variant', 'id' => $variant->id]) }}" class="variant-link">
                                            {{ $variant->name }}
                                            <i class="fas fa-chevron-right small opacity-25"></i>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">No series found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="faq-wrapper mt-5">
        <h4 class="font-weight-bold mb-4"><i class="fas fa-question-circle mr-2"></i>Common Questions</h4>
        <div class="row">
            <div class="col-lg-8">
                @foreach($faqs as $index => $faq)
                    <div class="faq-item shadow-sm">
                        <button class="faq-trigger" @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})">
                            <span class="font-weight-bold" style="font-size: 0.95rem;">{{ $faq['question'] }}</span>
                            <i class="fas fa-plus small text-muted transition" :class="activeFaq === {{ $index }} ? 'rotate-45' : ''"></i>
                        </button>
                        <div x-show="activeFaq === {{ $index }}" x-cloak x-collapse>
                            <div class="px-4 pb-4 text-muted small">
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