@extends('layouts.app')

@section('style')
<style>
/* Tab customization */
.nav-tabs .nav-link {
    font-weight: 500;
    color: #495057;
}
.nav-tabs .nav-link.active {
    font-weight: 600;
    color: #007bff;
    border-color: #007bff #007bff #fff;
}

/* Card for spare parts */
.spare-card {
    transition: 0.3s;
    cursor: pointer;
}
.spare-card:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
}

/* Search box style */
#searchInput {
    margin-bottom: 15px;
}

/* Category badges */
.category-badge {
    margin: 2px;
    cursor: pointer;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .spare-card { margin-bottom: 15px; }
}
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
                <a class="breadcrumb-item text-dark" href="#">{{ $model->brand->brand_name }}</a>
                <span class="breadcrumb-item active">{{ $variant->name }}</span>
            </nav>
        </div>
    </div>
</div>

<!-- Header -->
<div class="container-fluid px-xl-5 mb-3">
    <div class="bg-white p-4 shadow-sm rounded d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            @if($model->brand->brand_logo)
                <img src="{{ asset('storage/' . $model->brand->brand_logo) }}" style="width:50px; height:auto;">
            @endif
            Spare Parts – {{ $variant->name }}
        </h4>
        <span class="text-muted small">Showing all available parts for this variant</span>
    </div>
</div>

<!-- Tabs -->
<div class="container-fluid px-xl-5">
    <ul class="nav nav-tabs mb-3" id="spareTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab">Categories</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="search-tab" data-bs-toggle="tab" data-bs-target="#search" type="button" role="tab">Search</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="group-tab" data-bs-toggle="tab" data-bs-target="#group" type="button" role="tab">Group</button>
        </li>
    </ul>

    <div class="tab-content" id="spareTabContent">

        <!-- Categories Tab -->
        <div class="tab-pane fade show active" id="categories" role="tabpanel">
            <div class="mb-3">
                <input type="text" id="categorySearch" class="form-control" placeholder="Search category...">
            </div>
            <div class="mb-4">
                @foreach($categories as $category)
                    <span class="badge bg-primary category-badge" onclick="filterCategory('{{ $category->id }}')">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>

            <div class="row" id="sparePartsContainer">
                @foreach($variant->spareParts as $part)
                <div class="col-md-3">
                    <div class="card spare-card p-3 mb-3">
                        <img src="{{ asset('storage/' . $part->photo) }}" class="img-fluid mb-2" alt="{{ $part->name }}">
                        <h6 class="mb-1">{{ $part->name }}</h6>
                        <small class="text-muted">{{ $part->category->name ?? 'General' }}</small>
                        <p class="mb-0 text-truncate">{{ $part->description ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Search Tab -->
        <div class="tab-pane fade" id="search" role="tabpanel">
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search spare parts..." onkeyup="filterParts()">
            </div>

            <div class="row" id="searchPartsContainer">
                @foreach($variant->spareParts as $part)
                <div class="col-md-3 spare-item">
                    <div class="card spare-card p-3 mb-3">
                        <img src="{{ asset('storage/' . $part->photo) }}" class="img-fluid mb-2" alt="{{ $part->name }}">
                        <h6 class="mb-1">{{ $part->name }}</h6>
                        <small class="text-muted">{{ $part->category->name ?? 'General' }}</small>
                        <p class="mb-0 text-truncate">{{ $part->description ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Group Tab -->
        <div class="tab-pane fade" id="group" role="tabpanel">
            <p class="text-muted">Group functionality can be implemented here.</p>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
function filterCategory(categoryId) {
    let parts = document.querySelectorAll('#sparePartsContainer .col-md-3');
    parts.forEach(p => {
        if(p.querySelector('.text-muted').innerText == categoryId || categoryId == 'all') {
            p.style.display = 'block';
        } else {
            p.style.display = 'none';
        }
    });
}

function filterParts() {
    let query = document.getElementById('searchInput').value.toLowerCase();
    let parts = document.querySelectorAll('#searchPartsContainer .spare-item');
    parts.forEach(p => {
        let name = p.querySelector('h6').innerText.toLowerCase();
        if(name.includes(query)) {
            p.style.display = 'block';
        } else {
            p.style.display = 'none';
        }
    });
}

document.getElementById('categorySearch').addEventListener('keyup', function() {
    let query = this.value.toLowerCase();
    let badges = document.querySelectorAll('.category-badge');
    badges.forEach(b => {
        if(b.innerText.toLowerCase().includes(query)) {
            b.style.display = 'inline-block';
        } else {
            b.style.display = 'none';
        }
    });
});
</script>
@endsection
