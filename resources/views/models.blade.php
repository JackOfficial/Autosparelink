@extends('layouts.app')

@php
    $brandName = $brand->brand_name ?? 'Vehicle';
    $faqs = [
        ['question' => "How do I order $brandName parts online?", 'answer' => "Select your model, then the specific variant to view the parts catalog."],
        ['question' => "Are these genuine $brandName parts?", 'answer' => "Yes, we source genuine components for $brandName vehicles."],
        ['question' => "What if I can't find my VIN?", 'answer' => "You can browse by model and production year manually below."],
    ];
@endphp

@section('style')
<style>
    :root { --brand-primary: #007bff; --bg-soft: #f8f9fa; }
    body { background-color: var(--bg-soft) !important; font-family: 'Inter', sans-serif; }
    
    .custom-breadcrumb { padding: 15px 0; font-size: 0.9rem; }
    .custom-breadcrumb a { color: var(--brand-primary); text-decoration: none; }

    .hero-search {
        background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
        color: white;
        border-radius: 15px;
        padding: 3rem;
    }

    .model-card {
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 20px;
        background: #fff;
        height: 100%;
        transition: all 0.3s ease;
        margin-bottom: 30px; /* Bootstrap 4 gutter fix */
    }
    .model-card:hover {
        border-color: var(--brand-primary);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        transform: translateY(-5px);
    }

    .variant-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        border-radius: 8px;
        background: #f8f9fb;
        color: #444 !important;
        margin-bottom: 8px;
        text-decoration: none !important;
        transition: 0.2s;
    }
    .variant-link:hover { background: #eef4ff; color: var(--brand-primary) !important; }

    .rotate-180 { transform: rotate(180deg); }
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')
<div x-data="{ modelSearch: '', activeModel: null }">
    
    <div class="container-fluid px-lg-5">
        <nav class="custom-breadcrumb">
            <a href="{{ route('home') }}">Home</a> 
            <span class="mx-2 text-muted">/</span> 
            <span class="text-muted">{{ $brandName }}</span>
        </nav>

        <div class="hero-search shadow mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="font-weight-bold mb-3">Find Genuine {{ $brandName }} Parts</h1>
                    <p class="lead opacity-75">Access official technical diagrams by entering your VIN or Frame Number.</p>
                    
                    <form class="mt-4" method="GET" action="{{ route('brand.models', $brand?->id) }}">
                        <div class="input-group input-group-lg">
                            <input type="text" name="query" class="form-control border-0" 
                                   placeholder="Enter 17-digit VIN..." value="{{ request('query') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary px-4 shadow-none">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-right">
                    <i class="fas fa-car fa-10x" style="opacity: 0.1;"></i>
                </div>
            </div>
        </div>

        <div class="row align-items-end mb-4">
            <div class="col-md-8">
                <h2 class="font-weight-bold mb-0">Browse by Model</h2>
                <p class="text-muted">Select a model series for {{ $brandName }}</p>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0 rounded-left-pill">
                            <i class="fas fa-filter text-muted"></i>
                        </span>
                    </div>
                    <input type="text" x-model="modelSearch" class="form-control border-left-0 rounded-right-pill" 
                           placeholder="Filter models...">
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($models as $model)
                <div class="col-xl-3 col-lg-4 col-md-6" 
                     x-show="modelSearch === '' || '{{ strtolower($model->model_name) }}'.includes(modelSearch.toLowerCase())">
                    
                    <div class="model-card shadow-sm">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="font-weight-bold mb-1">{{ $model->model_name }}</h5>
                                <span class="badge badge-light border text-muted">
                                    {{ $model->production_start_year ?? '?' }} - {{ $model->production_end_year ?? 'Present' }}
                                </span>
                            </div>
                            @if($brand && $brand->brand_logo)
                                <img src="{{ asset('storage/' . $brand->brand_logo) }}" width="30" style="opacity:0.4">
                            @endif
                        </div>

                        <hr class="my-3">

                        @if($model->variants->count())
                            <button @click="activeModel = (activeModel === {{ $model->id }} ? null : {{ $model->id }})" 
                                    class="btn btn-outline-primary btn-sm btn-block d-flex justify-content-between align-items-center">
                                <span>{{ $model->variants->count() }} Variants</span>
                                <i class="fas fa-chevron-down transition" :class="activeModel === {{ $model->id }} ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="activeModel === {{ $model->id }}" x-cloak class="mt-3">
                                @foreach($model->variants as $variant)
                                    <a href="{{ route('specifications.show', ['type' => 'variant', 'id' => $variant->id]) }}" 
                                       class="variant-link">
                                        <span>{{ $variant->name }}</span>
                                        <i class="fas fa-arrow-right small opacity-50"></i>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <a href="{{ route('specifications.show', ['type' => 'model', 'id' => $model->id]) }}" 
                               class="btn btn-primary btn-sm btn-block">View Catalog</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">No models found.</h4>
                </div>
            @endforelse
        </div>

        <div class="row mt-5 pb-5">
            <div class="col-lg-5 mb-4">
                <div class="bg-white p-4 rounded shadow-sm border h-100">
                    <h4 class="font-weight-bold mb-3">About {{ $brandName }}</h4>
                    <p class="text-muted">{{ $brand?->description ?? "Genuine parts for $brandName." }}</p>
                </div>
            </div>
            <div class="col-lg-7 mb-4">
                <div class="bg-white p-4 rounded shadow-sm border">
                    <h4 class="font-weight-bold mb-4">Common Questions</h4>
                    <div class="accordion" id="faqAccordion">
                        @foreach($faqs as $key => $faq)
                            <div class="card border-0 mb-2">
                                <div class="card-header bg-light border-0 rounded" id="heading{{ $key }}">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link btn-block text-left text-dark font-weight-bold text-decoration-none shadow-none" 
                                                type="button" data-toggle="collapse" data-target="#collapse{{ $key }}">
                                            {{ $faq['question'] }}
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapse{{ $key }}" class="collapse" data-parent="#faqAccordion">
                                    <div class="card-body text-muted">{{ $faq['answer'] }}</div>
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