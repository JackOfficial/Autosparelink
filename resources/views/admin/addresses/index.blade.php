@extends('admin.layouts.app')

@section('content')
{{-- Ensure Alpine.js is loaded in your layouts, or include it here --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="container-fluid py-4" x-data="{ search: '' }">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="h4 mb-0">Customer Addresses</h2>
            <p class="text-muted small mb-0">Manage shipping destinations for all system users</p>
        </div>
        
        <div class="d-flex align-items-center mt-3 mt-md-0">
            {{-- Search Bar --}}
            <div class="position-relative mr-3">
                <span class="position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); z-index: 5;">
                    <i class="fas fa-search text-muted small"></i>
                </span>
                <input 
                    type="text" 
                    x-model="search" 
                    placeholder="Search name, street, or city..." 
                    class="form-control form-control-sm pl-5 shadow-sm border-0" 
                    style="width: 280px; height: 38px;"
                >
                {{-- Clear Search Button --}}
                <button 
                    x-show="search.length > 0" 
                    @click="search = ''" 
                    class="btn btn-link position-absolute text-muted p-0" 
                    style="right: 12px; top: 50%; transform: translateY(-50%); z-index: 5;"
                >
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>

            <a href="{{ route('admin.addresses.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle mr-1"></i> Add New
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Address Grid --}}
    <div class="row">
        @forelse($addresses as $address)
            {{-- Alpine-powered filter logic --}}
            <div class="col-xl-4 col-md-6 mb-4" 
                 x-show="search === '' || 
                         '{{ strtolower($address->full_name) }}'.includes(search.toLowerCase()) || 
                         '{{ strtolower($address->street_address) }}'.includes(search.toLowerCase()) || 
                         '{{ strtolower($address->city) }}'.includes(search.toLowerCase()) ||
                         '{{ strtolower($address->user?->name) }}'.includes(search.toLowerCase())"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                
                <div class="card h-100 border-0 shadow-sm {{ $address->is_default ? 'border-left-primary' : '' }}">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                        <span class="badge {{ $address->is_default ? 'badge-primary' : 'badge-light text-muted' }}">
                            {{ $address->is_default ? 'DEFAULT ADDRESS' : 'SECONDARY' }}
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light text-muted" type="button" data-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('admin.addresses.edit', $address->id) }}">
                                    <i class="fas fa-edit mr-2 text-warning"></i> Edit
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('admin.addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Delete this address?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-trash mr-2"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="mb-3">
                            <h5 class="card-title mb-0">{{ $address->full_name }}</h5>
                            <small class="text-primary font-weight-bold">
                                <i class="fas fa-user-circle mr-1"></i> {{ $address->user?->name ?? 'Unassigned User' }}
                            </small>
                        </div>

                        <div class="text-dark small mb-3">
                            <p class="mb-1"><i class="fas fa-map-marker-alt text-muted mr-2"></i> {{ $address->street_address }}</p>
                            <p class="mb-1"><i class="fas fa-city text-muted mr-2"></i> {{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }}</p>
                            <p class="mb-1"><i class="fas fa-globe-africa text-muted mr-2"></i> {{ $address->country }} {{ $address->postal_code }}</p>
                            <p class="mb-0"><i class="fas fa-phone text-muted mr-2"></i> {{ $address->phone }}</p>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 d-flex justify-content-between">
                        <a href="{{ route('admin.addresses.show', $address->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye mr-1"></i> Details
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="copyAddress('{{ $address->id }}')">
                            <i class="fas fa-copy mr-1"></i> Copy for Label
                        </button>
                    </div>
                </div>

                <textarea id="addrText_{{ $address->id }}" class="d-none">{{ $address->full_name }}&#10;{{ $address->street_address }}&#10;{{ $address->city }}, {{ $address->country }}&#10;Phone: {{ $address->phone }}</textarea>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted">No addresses found in the system.</p>
            </div>
        @endforelse
    </div>

    {{-- Empty State for Search --}}
    <div x-show="search !== '' && document.querySelectorAll('.col-xl-4:not([style*=\'display: none\'])').length === 0" 
         class="text-center py-5" x-cloak>
        <i class="fas fa-search-minus fa-3x text-muted mb-3"></i>
        <p class="text-muted">No addresses match "<span x-text="search"></span>"</p>
    </div>

    <div class="mt-4" x-show="search === ''">
        {{ $addresses->links() }}
    </div>
</div>

<script>
function copyAddress(id) {
    const text = document.getElementById('addrText_' + id).value;
    navigator.clipboard.writeText(text).then(() => {
        alert('Address copied to clipboard!');
    });
}
</script>

<style>
    [x-cloak] { display: none !important; }
    .pl-5 { padding-left: 2.5rem !important; }
</style>
@endsection