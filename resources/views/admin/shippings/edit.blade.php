@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">Edit Shipping Record</h2>
                    <span class="text-muted small">Update logistics for <strong>Order #{{ $shipping->order_id }}</strong></span>
                </div>
                <a href="{{ route('admin.shippings.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
            </div>

            <div class="row">
                {{-- Main Form Column --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('admin.shippings.update', $shipping->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold small text-uppercase text-muted">Carrier Service</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="fas fa-truck text-primary"></i></span>
                                        </div>
                                        <input type="text" name="carrier" 
                                               value="{{ old('carrier', $shipping->carrier) }}" 
                                               class="form-control @error('carrier') is-invalid @enderror" 
                                               placeholder="e.g. DHL, FedEx, Local Courier">
                                    </div>
                                    @error('carrier') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold small text-uppercase text-muted">Tracking Number</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="fas fa-barcode text-primary"></i></span>
                                        </div>
                                        <input type="text" name="tracking_number" 
                                               value="{{ old('tracking_number', $shipping->tracking_number) }}" 
                                               class="form-control @error('tracking_number') is-invalid @enderror" 
                                               placeholder="Enter unique tracking ID">
                                    </div>
                                    @error('tracking_number') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold small text-uppercase text-muted">Current Status</label>
                                    <select name="status" class="form-control custom-select">
                                        @foreach(['pending' => 'Pending', 'shipped' => 'Shipped', 'in_transit' => 'In Transit', 'delivered' => 'Delivered', 'failed' => 'Failed / Returned'] as $value => $label)
                                            <option value="{{ $value }}" {{ $shipping->status == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <hr class="my-4">

                                <button type="submit" class="btn btn-primary btn-block shadow-sm py-2 font-weight-bold">
                                    <i class="fas fa-save mr-1"></i> Update Logistics Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Side Info Column --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-uppercase small mb-3">Shipment Info</h6>
                            <div class="mb-3">
                                <label class="text-muted small d-block mb-0">Customer</label>
                                <span class="font-weight-bold text-dark">
                                    {{ $shipping->order?->guest_name ?? $shipping->order?->user?->name ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block mb-0">Created On</label>
                                <span class="text-dark">{{ $shipping->created_at->format('d M, Y') }}</span>
                            </div>
                            <div class="alert alert-info border-0 small mb-0">
                                <i class="fas fa-info-circle mr-1"></i>
                                Updating status to <strong>Delivered</strong> will automatically notify the customer if email triggers are enabled.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection