<x-shop-dashboard>
    <x-slot:title>Add New Spare Part</x-slot:title>

    {{-- Content Header (Mimicking your example style) --}}
    <section class="content-header">
        <div class="container-fluid py-3">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i>Add Spare Part</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('shop.dashboard') }}" class="text-decoration-none"><i class="fas fa-home"></i> Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('shop.parts.index') }}" class="text-decoration-none">Inventory</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        {{-- Professional Card Header --}}
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-info-circle me-2 text-muted"></i>Part Specifications
                            </h5>
                        </div>
                         <livewire:shop.parts.parts-component />
                        {{-- Card Body --}}
                        <div class="card-body p-4">
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .breadcrumb-item + .breadcrumb-item::before { content: "›"; }
        .card-title { font-size: 1rem; color: #4a5568; }
        .content-header h1 { font-size: 1.5rem; }
    </style>
    @endpush
</x-shop-dashboard>