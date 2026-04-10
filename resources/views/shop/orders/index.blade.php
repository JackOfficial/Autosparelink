<x-shop-dashboard>
    <x-slot:title>My Inventory</x-slot:title>

    <div class="container-fluid py-4" x-data="{ 
        search: '',
        showRow(name, sku) {
            const term = this.search.toLowerCase();
            return name.toLowerCase().includes(term) || sku.toLowerCase().includes(term);
        }
    }">
        {{-- Header Section --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h1 class="h3 fw-bold text-dark mb-1">My Inventory</h1>
                <p class="text-muted small">Manage your spare parts, stock levels, and pricing.</p>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end gap-3 mt-3 mt-md-0">
                <div class="position-relative w-100" style="max-width: 300px;">
                    <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                        <i class="fas fa-search"></i>
                    </span>
                    <input 
                        type="text" 
                        x-model="search" 
                        class="form-control ps-5 border-0 shadow-sm" 
                        placeholder="Search parts or SKU..."
                        style="border-radius: 10px; height: 42px;"
                    >
                </div>
                <a href="{{ route('shop.parts.create') }}" class="btn btn-primary d-flex align-items-center px-3" style="border-radius: 10px;">
                    <i class="fas fa-plus me-2"></i> Add Part
                </a>
            </div>
        </div>

        {{-- Stats Overview --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3" style="border-radius: 12px;">
                    <div class="text-muted small fw-bold text-uppercase">Total Parts</div>
                    <div class="h4 fw-bold mb-0">{{ $parts->total() }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3" style="border-radius: 12px;">
                    <div class="text-muted small fw-bold text-uppercase">Out of Stock</div>
                    <div class="h4 fw-bold mb-0 text-danger">{{ $parts->where('stock', 0)->count() }}</div>
                </div>
            </div>
        </div>

        {{-- Inventory Table --}}
        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 text-muted small fw-bold text-uppercase">Part Info</th>
                            <th class="py-3 border-0 text-muted small fw-bold text-uppercase">Category</th>
                            <th class="py-3 border-0 text-muted small fw-bold text-uppercase">Price</th>
                            <th class="py-3 border-0 text-muted small fw-bold text-uppercase">Stock</th>
                            <th class="pe-4 py-3 border-0 text-center text-muted small fw-bold text-uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parts as $part)
                            <tr x-show="showRow('{{ addslashes($part->name) }}', '{{ $part->sku ?? '' }}')" x-transition>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3 d-none d-sm-block">
                                            <i class="fas fa-tools text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $part->name }}</div>
                                            <div class="text-muted small">SKU: {{ $part->sku ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-soft-info text-info px-2 py-1">
                                        {{ $part->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="fw-bold">
                                    {{ number_format($part->price) }} <span class="small text-muted">RWF</span>
                                </td>
                                <td>
                                    @if($part->stock <= 5)
                                        <span class="text-danger fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> {{ $part->stock }}</span>
                                    @else
                                        <span class="text-dark">{{ $part->stock }}</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-center">
                                    <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                        <a href="{{ route('shop.parts.edit', $part->id) }}" class="btn btn-white btn-sm border-end" title="Edit">
                                            <i class="fas fa-edit text-muted"></i>
                                        </a>
                                        <form action="{{ route('shop.parts.destroy', $part->id) }}" method="POST" onsubmit="return confirm('Delete this part?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-white btn-sm" title="Delete">
                                                <i class="fas fa-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-muted">
                                    No parts found in your inventory.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-0 py-3">
                {{ $parts->links() }}
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .bg-soft-info { background-color: #e0f7fa; }
        .btn-white { background: #fff; border: 1px solid #edf2f7; }
        .btn-white:hover { background: #f8fafc; }
        /* Smooth transitions for Alpine x-show */
        [x-cloak] { display: none !important; }
    </style>
    @endpush
</x-shop-dashboard>