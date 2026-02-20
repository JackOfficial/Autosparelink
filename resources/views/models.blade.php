@extends('layouts.app')

@php
    $brand = $models->first()?->brand;
@endphp

@section('style')
<style>
    :root { --brand-primary: #007bff; --bg-soft: #f8f9fa; }
    body { background: var(--bg-soft) !important; font-family: 'Inter', sans-serif; }
    
    /* Modern Glass Effect */
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

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
        transition: 0.3s;
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
        text-decoration: none;
        color: #444;
        margin-bottom: 5px;
        background: #f8f9fb;
        transition: 0.2s;
    }
    .variant-link:hover { background: #eef4ff; color: var(--brand-primary); }

    .rotate-180 { transform: rotate(180deg); }
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')
<div x-data="{ 
    modelSearch: '', 
    activeModel: null,
    itemsCount: {{ $models->count() }}
}">

    <div class="container-fluid px-xl-5 mt-4">
        <div class="hero-search p-5 shadow-lg mb-5">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-5 fw-bold mb-2">Find Genuine {{ $brand?->brand_name }} Parts</h1>
                    <p class="opacity-75">Access the official technical catalog by entering your vehicle identification.</p>
                    
                    <form class="mt-4" method="GET" action="{{ route('brand.models', $brand?->id) }}">
                        <div class="input-group input-group-lg shadow">
                            <input type="text" name="query" class="form-control border-0" 
                                   placeholder="Enter 17-digit VIN or Frame Number..." value="{{ request('query') }}">
                            <button class="btn btn-primary px-4">Identify Vehicle</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center">
                    <i class="fas fa-microchip fa-10x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-xl-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-4 gap-3">
            <div>
                <h2 class="fw-bold mb-0">Browse by Model</h2>
                <p class="text-muted">Select a model series to see specific variants.</p>
            </div>
            <div class="col-md-4">
                <div class="position-relative">
                    <i class="fas fa-filter position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" x-model="modelSearch" class="form-control ps-5 rounded-pill" 
                           placeholder="Filter models (e.g. RAV4, Camry)...">
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($models as $model)
                <div class="col-xl-3 col-lg-4 col-md-6" 
                     x-show="modelSearch === '' || '{{ strtolower($model->model_name) }}'.includes(modelSearch.toLowerCase())"
                     x-transition>
                    
                    <div class="model-card shadow-sm">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $model->model_name }}</h5>
                                <span class="badge bg-light text-muted border">
                                    {{ $model->production_start_year ?? '?' }} - {{ $model->production_end_year ?? 'Present' }}
                                </span>
                            </div>
                            @if($brand->brand_logo)
                                <img src="{{ asset('storage/' . $brand->brand_logo) }}" width="30" class="opacity-50">
                            @endif
                        </div>

                        <hr class="my-3 opacity-50">

                        @if($model->variants->count())
                            <button @click="activeModel = (activeModel === {{ $model->id }} ? null : {{ $model->id }})" 
                                    class="btn btn-outline-primary btn-sm w-100 d-flex justify-content-between align-items-center">
                                <span>{{ $model->variants->count() }} Variants Found</span>
                                <i class="fas fa-chevron-down transition" :class="activeModel === {{ $model->id }} ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="activeModel === {{ $model->id }}" 
                                 x-collapse x-cloak class="mt-3">
                                @foreach($model->variants as $variant)
                                    <a href="{{ route('specifications.show', ['type' => 'variant', 'id' => $variant->id]) }}" 
                                       class="variant-link">
                                        <div>
                                            <span class="fw-semibold">{{ $variant->name }}</span>
                                            <div class="small text-muted">{{ $variant->engine_type?->name }}</div>
                                        </div>
                                        <i class="fas fa-arrow-right fa-xs opacity-50"></i>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <a href="{{ route('specifications.show', ['type' => 'model', 'id' => $model->id]) }}" 
                               class="btn btn-primary btn-sm w-100">View Catalog</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <img src="https://illustrations.popsy.co/gray/box.svg" width="200" class="mb-3">
                    <h4 class="text-muted">No models found in this category.</h4>
                </div>
            @endforelse
        </div>
    </div>

    <div class="container-fluid px-xl-5 mt-5 pb-5">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-info-circle text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-0">About {{ $brand?->brand_name }}</h4>
                    </div>
                    <p class="text-muted" style="line-height: 1.7;">{{ $brand?->description }}</p>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <h4 class="fw-bold mb-4">Common Questions</h4>
                    <div class="accordion accordion-flush" id="faqGrid">
                        @foreach($faqs as $key => $faq)
                            <div class="accordion-item border-0 mb-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button rounded-3 collapsed bg-light mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#f{{ $key }}">
                                        {{ $faq['question'] }}
                                    </button>
                                </h2>
                                <div id="f{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#faqGrid">
                                    <div class="accordion-body text-muted">{{ $faq['answer'] }}</div>
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