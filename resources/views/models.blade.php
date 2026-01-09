@extends('layouts.app')

@section('style')
<style>
/* -------------------------- */
/* GENERAL PAGE REFINEMENTS   */
/* -------------------------- */
body { background: #f5f7fa !important; }
.breadcrumb { background: #ffffff !important; border-radius: 6px; }

/* Search Box */
.genuine-search-box { border-left: 4px solid #007bff; }

/* -------------------------- */
/* MODEL CARD                 */
/* -------------------------- */
.model-card {
    background: #fff;
    border-radius: 12px;
    padding: 18px;
    transition: all .25s ease-in-out;
    border: 1px solid #e6e6e6;
}
.model-card:hover {
    transform: translateY(-4px);
    box-shadow: 0px 8px 18px rgba(0,0,0,0.08);
}

/* Model Name */
.model-card h6 a { font-weight: 600; color: #222; }
.model-card h6 a:hover { color: #007bff !important; }

/* Production Years */
.model-years { font-size: 13px; color: #888; }

/* Chevron Button */
.toggle-btn { border: none; background: transparent; padding: 4px; }
.toggle-btn i { transition: 0.3s ease; font-size: 14px; }

/* Variant items */
.variant-item {
    font-size: 13px;
    padding: 6px 0;
    color: #444;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.model-name-disabled {
    cursor: default;
    opacity: 0.85;
}

.variant-item:hover { background: #f1f5ff; }
.variant-badge { font-size: 10px; padding: 2px 5px; margin-left: 3px; border-radius: 4px; }

/* Model Grid Spacing */
.model-container { margin-bottom: 25px; }

#faqAccordion a { text-decoration: none; padding: 10px 0; }
#faqAccordion i { transition: transform .3s ease; }
#faqAccordion i.rotate { transform: rotate(180deg); }

/* Responsive Adjustments */
@media(max-width: 768px) { h3 { font-size: 20px; } }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="container-fluid px-xl-5 mt-3">
    <nav class="breadcrumb mb-3">
        <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
        <span class="breadcrumb-item active">Locate Genuine Parts</span>
    </nav>
</div>

<!-- Page Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded genuine-search-box mb-4">
        <h3 class="text-uppercase mb-1 fw-bold">
            Genuine Parts Locator
        </h3>
        <small class="text-muted">
            Enter VIN or Frame Number to search genuine {{ $brand->brand_name }} parts
        </small>
    </div>
</div>

<!-- Search Box -->
<div class="container-fluid px-xl-5 mb-4">
    <div class="bg-white p-4 shadow-sm rounded">
        <form class="row g-3 align-items-center" method="GET" action="{{ route('brand.models', $brand->id) }}">
            <div class="col-lg-10 col-md-9 col-sm-12 mb-2">
                <input 
                    type="text" 
                    name="query"
                    class="form-control form-control-lg" 
                    placeholder="Enter VIN or Frame Number (e.g., JTMHX09J604123456)"
                    value="{{ request('query') }}"
                >
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12 mb-2">
                <button class="btn btn-primary btn-lg w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Brand Models Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <div>
            <h4 class="text-uppercase mb-1 fw-bold">
                @if($brand->brand_logo)
                    <img src="{{ asset('storage/' . $brand->brand_logo) }}" 
                         style="width: 45px; margin-top:-5px;" />
                @endif
                {{ $brand->brand_name }} Models
            </h4>
            <small class="text-muted">
                Select your vehicle model to browse the parts catalog
            </small>
        </div>
        <div class="mt-3 mt-md-0">
            <span class="text-muted small">
                Showing <strong>{{ count($models) }}</strong> models
            </span>
        </div>
    </div>
</div>

<!-- Models Grid -->
<div class="container-fluid px-xl-5">
    <div class="row">
        @forelse($models as $model)
            <div class="col-md-3 col-sm-6 model-container">
                <div class="model-card h-100">
                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-car-side text-primary me-2"></i>
                           <h6 class="mb-0">
    @if($model->variants->count() > 0)
        <!-- Model has variants: NOT clickable -->
        <span class="fw-semibold text-dark model-name-disabled">
            {{ $model->model_name }}
        </span>
    @else
        <!-- Model has NO variants: clickable -->
        <a href="{{ route('model.specification', $model->id) }}">
            {{ $model->model_name }}
        </a>
    @endif
</h6>

                        </div>
                        @if($model->variants->count() > 0)
                        <button class="toggle-btn" type="button" data-toggle="collapse" data-target="#variants{{ $model->id }}">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        @endif
                    </div>

                    <div class="model-years mt-2">
                        {{ $model->production_start_year ?? '?' }} - {{ $model->production_end_year ?? '?' }}
                    </div>

                    <!-- Variants -->
                    @if($model->variants->count() > 0)
                    <div class="collapse mt-3" id="variants{{ $model->id }}">
                        <div class="list-group list-group-flush">
                            @foreach($model->variants as $variant)
                            <a href="{{ route('variant.specification', $variant->id) }}" class="list-group-item bg-transparent border-0 variant-item">
                                <span>
                                    <i class="fas fa-circle"></i>
                                    {{ $variant->name ?? $variant->body_type->name ?? 'Variant' }}
                                </span>
                                <span>
                                    @if($variant->engine_type)
                                        <span class="badge bg-info text-white variant-badge">{{ $variant->engine_type->name }}</span>
                                    @endif
                                    @if($variant->transmission_type)
                                        <span class="badge bg-secondary text-white variant-badge">{{ $variant->transmission_type->name }}</span>
                                    @endif
                                    @if($variant->body_type)
                                        <span class="badge bg-warning text-dark variant-badge">{{ $variant->body_type->name }}</span>
                                    @endif
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @empty
        <div class="col-md-12 py-4">
            <p class="text-center text-muted">
                No models found for {{ $brand->brand_name }} at the moment.
            </p>
        </div>
        @endforelse
    </div>
</div>

<!-- Brand Description -->
@if($brand->description)
<div class="container-fluid px-xl-5 mt-4 mb-5">
    <div class="bg-white p-4 shadow-sm rounded brand-description-box">
        <div class="d-flex align-items-center mb-2">
            @if($brand->brand_logo)
                <img src="{{ asset('storage/'.$brand->brand_logo) }}" style="width:50px" class="me-3">
            @endif
            <h4 class="mb-0 font-weight-bold text-uppercase">About {{ $brand->brand_name }}</h4>
        </div>
        <p class="brand-description-text mb-0">{{ $brand->description }}</p>
    </div>
</div>
@endif

<!-- FAQ Section -->
<div class="container-fluid px-xl-5 mt-4 mb-5">
    <div class="bg-white p-4 shadow-sm rounded">
        <h4 class="font-weight-bold text-uppercase mb-4">FAQ</h4>
        <div id="faqAccordion">
            @php
                $faqs = [
                    ['question' => "How do I order {$brand->brand_name} parts online?", 'answer' => "Select your brand, then the specification and part. Click Add, then Checkout."],
                    ['question' => "Are {$brand->brand_name} parts expensive?", 'answer' => "Parts are affordable thanks to trusted suppliers."],
                    ['question' => "Can I buy genuine {$brand->brand_name} parts here?", 'answer' => "Yes, all catalog parts are genuine."],
                    ['question' => "How long does it take to get {$brand->brand_name} parts?", 'answer' => "Orders are shipped the next business day. Delivery typically takes 3–7 business days."]
                ];
            @endphp
            @foreach($faqs as $key => $faq)
            <div class="mb-3">
                <a class="d-flex justify-content-between align-items-center text-dark font-weight-bold {{ $key !== 0 ? 'collapsed' : '' }}" data-toggle="collapse" href="#faq{{ $key }}">
                    {{ $faq['question'] }}
                    <i class="fas fa-chevron-down"></i>
                </a>
                <div id="faq{{ $key }}" class="collapse {{ $key === 0 ? 'show' : '' }}" data-parent="#faqAccordion">
                    <div class="pt-3 text-muted" style="line-height:1.8">{{ $faq['answer'] }}</div>
                </div>
            </div>
            @if(!$loop->last) <hr> @endif
            @endforeach
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#faqAccordion .collapse').on('show.bs.collapse', function () {
        $(this).siblings('a').find('i').addClass('rotate');
    });
    $('#faqAccordion .collapse').on('hide.bs.collapse', function () {
        $(this).siblings('a').find('i').removeClass('rotate');
    });
});
</script>

@endsection
