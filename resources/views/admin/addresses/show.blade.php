@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0">Address Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.addresses.index') }}">Addresses</a></li>
                    <li class="breadcrumb-item active">{{ $address->full_name }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group shadow-sm">
            <a href="{{ route('admin.addresses.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
            <a href="{{ route('admin.addresses.edit', $address->id) }}" class="btn btn-warning text-white">
                <i class="fas fa-edit mr-1"></i> Edit Address
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: User & Meta --}}
        <div class="col-md-4">
            {{-- Owner Card --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Owner Information</div>
                <div class="card-body text-center">
                    <div class="avatar-placeholder mb-3">
                        <i class="fas fa-user-circle fa-4x text-light"></i>
                    </div>
                    <h5 class="mb-0">{{ $address->user?->name ?? 'Deleted User' }}</h5>
                    <p class="text-muted small mb-3">{{ $address->user?->email ?? 'No email' }}</p>
                    
                    @if($address->is_default)
                        <span class="badge badge-pill badge-primary px-3 py-2">
                            <i class="fas fa-star mr-1"></i> Default Shipping Address
                        </span>
                    @endif
                </div>
            </div>

            {{-- Communication Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Direct Contact</div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-light mr-3"><i class="fas fa-phone text-muted"></i></div>
                        <div>
                            <small class="text-muted d-block">Phone Number</small>
                            <span class="font-weight-bold">{{ $address->phone }}</span>
                        </div>
                    </div>
                    <a href="tel:{{ $address->phone }}" class="btn btn-block btn-outline-primary btn-sm">
                        <i class="fas fa-phone-alt mr-1"></i> Call Customer
                    </a>
                </div>
            </div>
        </div>

        {{-- Right Column: Address & Orders --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold text-uppercase small d-flex justify-content-between align-items-center">
                    <span>Shipping Destination</span>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address->street_address . ' ' . $address->city . ' ' . $address->country) }}" 
                       target="_blank" class="btn btn-sm btn-link text-primary p-0">
                        <i class="fas fa-map-marked-alt mr-1"></i> Open in Maps
                    </a>
                </div>
                <div class="card-body bg-light rounded m-3 border">
                    <div class="row">
                        <div class="col-sm-6">
                            <h6 class="text-muted text-uppercase small font-weight-bold">Street Address</h6>
                            <p class="h5 text-dark">{{ $address->street_address }}</p>
                            
                            <h6 class="text-muted text-uppercase small font-weight-bold mt-4">City / Region</h6>
                            <p class="text-dark">{{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="text-muted text-uppercase small font-weight-bold">Postal Code</h6>
                            <p class="text-dark font-italic">{{ $address->postal_code ?? 'None' }}</p>
                            
                            <h6 class="text-muted text-uppercase small font-weight-bold mt-4">Country</h6>
                            <p class="text-dark h5"><i class="fas fa-globe-africa mr-2"></i>{{ $address->country }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Recent Orders to this Address</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light small">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 
                                    Assuming you have an Order relationship on the Address model.
                                    If not, this part can be hidden.
                                --}}
                                @forelse($address->orders()->latest()->take(5)->get() as $order)
                                    <tr>
                                        <td class="font-weight-bold">#{{ $order->id }}</td>
                                        <td class="small text-muted">{{ $order->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <span class="badge badge-pill badge-soft-primary border px-2">{{ $order->status }}</span>
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">No recent orders found for this location.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    .badge-soft-primary {
        background-color: #eef2ff;
        color: #4f46e5;
    }
</style>
@endsection