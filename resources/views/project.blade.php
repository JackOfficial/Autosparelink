@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')

@section('content')

<div class="container mt-5">

    <!-- Product Header -->
    <div class="row mb-4">
        <div class="col-md-6 text-center">
            <!-- Main Photo -->
            @if($mainPhoto)
                <img src="{{ asset('storage/' . $mainPhoto->photo_url) }}" 
                     class="img-fluid rounded" 
                     alt="{{ $part->part_name }}">
            @else
                <img src="{{ asset('images/no-image.png') }}" 
                     class="img-fluid rounded" 
                     alt="No Image">
            @endif
        </div>
        <div class="col-md-6">
            <h2>{{ $part->part_name }}</h2>
            <p><strong>Part Number:</strong> {{ $part->part_number ?? '—' }}</p>
            <p><strong>Brand:</strong> {{ optional($part->partBrand)->name ?? '—' }}</p>
            <p><strong>Category:</strong> {{ optional($part->category)->category_name ?? '—' }}</p>
            <p><strong>Price:</strong> {{ number_format($part->price, 2) }} RWF</p>
            <p><strong>Stock:</strong> {{ $part->stock_quantity }}</p>
            <p><strong>Status:</strong> {{ $part->status == 1 ? 'Active' : 'Inactive' }}</p>
        </div>
    </div>

    <!-- Description -->
    <div class="row mb-4">
        <div class="col-12">
            <h4>Description</h4>
            <p>{!! $part->description ?? 'No description available.' !!}</p>
        </div>
    </div>

    <!-- Photos Gallery -->
    @if($photos->count() > 1)
    <div class="row mb-4">
        <div class="col-12">
            <h4>Gallery</h4>
            <div class="d-flex flex-wrap gap-2">
                @foreach($photos as $photo)
                    <img src="{{ asset('storage/' . $photo->photo_url) }}" 
                         class="img-thumbnail" 
                         width="120" 
                         alt="Photo">
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Compatibility -->
  <div class="row mb-4">
    <div class="col-12">
        <h4>Compatibility jack</h4>
        {{ $compatibilities }}
        <d>/////////////////////////////////////////////////////</d>
        @if($compatibilities->isEmpty())
            <p class="text-muted">No compatibility info available.</p>
        @else
            <ul>
                @foreach($compatibilities as $variant)
                    <li>
                        <strong>Vehicle:</strong>
                        {{ optional($variant->vehicleModel->brand)->brand_name ?? '—' }} /
                        {{ optional($variant->vehicleModel)->model_name ?? '—' }} —
                        {{ $variant->name ?? '—' }}

                        ({{ $variant->pivot->year_start ?? '-' }}
                        – {{ $variant->pivot->year_end ?? '-' }})
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>


    <!-- Substitutions -->
    <div class="row mb-4">
        <div class="col-12">
            <h4>Substitutions</h4>
            @if($substitutions->isEmpty())
                <p class="text-muted">No alternative parts available.</p>
            @else
                <ul>
                    @foreach($substitutions as $sub)
                        <li>
                            <a href="{{ url('/shop/products/' . $sub->id) }}">
                                {{ $sub->part_name }} ({{ $sub->part_number ?? '—' }}) - 
                                Brand: {{ optional($sub->partBrand)->name ?? '—' }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</div>

@endsection
