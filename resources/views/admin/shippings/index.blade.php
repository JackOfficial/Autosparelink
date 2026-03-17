@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Shipping & Logistics</h2>
            <p class="text-muted small mb-0">Manage outbound shipments and tracking updates</p>
        </div>
        <a href="{{ route('admin.shippings.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus mr-1"></i> Create New Shipment
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted text-uppercase" style="font-size: 0.75rem;">
                        <tr>
                            <th class="border-0 px-4">Ship #</th>
                            <th class="border-0">Order</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Carrier</th>
                            <th class="border-0">Tracking Number</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippings as $shipping)
                            <tr>
                                <td class="px-4 font-weight-bold">#{{ $shipping->id }}</td>
                                <td>
                                    @if($shipping->order)
                                        <a href="{{ route('admin.orders.show', $shipping->order->id) }}" class="font-weight-bold text-primary">
                                            Order #{{ $shipping->order->id }}
                                        </a>
                                    @else
                                        <span class="badge badge-soft-danger text-danger">Order Deleted</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark">{{ $shipping->order?->user?->name ?? 'Unknown' }}</span>
                                        <small class="text-muted">{{ $shipping->order?->user?->email ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark font-weight-bold">
                                        <i class="fas fa-truck-moving mr-1 text-muted small"></i> 
                                        {{ $shipping->carrier }}
                                    </span>
                                </td>
                                <td>
                                    @if($shipping->tracking_number)
                                        <div class="input-group input-group-sm" style="max-width: 180px;">
                                            <input type="text" class="form-control bg-light border-0 small" value="{{ $shipping->tracking_number }}" readonly id="track_{{ $shipping->id }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary border-0" type="button" onclick="copyToClipboard('track_{{ $shipping->id }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">Not Assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'delivered'  => 'badge-success',
                                            'shipped'    => 'badge-primary',
                                            'in_transit' => 'badge-info',
                                            'pending'    => 'badge-warning',
                                            'failed'     => 'badge-danger'
                                        ][$shipping->status] ?? 'badge-secondary';
                                    @endphp
                                    <span class="badge {{ $statusClass }} py-2 px-3">
                                        {{ ucfirst(str_replace('_', ' ', $shipping->status)) }}
                                    </span>
                                </td>
                                <td class="text-center px-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.shippings.show', $shipping->id) }}" class="btn btn-sm btn-outline-info" title="View Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.shippings.edit', $shipping->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Status">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.shippings.destroy', $shipping->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove shipping record?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                    No shipping records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $shippings->links() }}
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    var copyText = document.getElementById(elementId);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    alert("Tracking number copied: " + copyText.value);
}
</script>
@endsection