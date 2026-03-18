@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">Initialize New Shipment</h2>
                    <p class="text-muted small mb-0">Link an order to a logistics carrier and generate tracking</p>
                </div>
                <a href="{{ route('admin.shippings.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>

            <form method="POST" action="{{ route('admin.shippings.store') }}">
                @csrf
                <div class="row">
                    {{-- Left Column: Order Selection & Details --}}
                    <div class="col-lg-7">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold small text-uppercase text-muted">Select Target Order</label>
                                    <select name="order_id" class="form-control select2 @error('order_id') is-invalid @enderror">
                                        <option value="">-- Choose an Order --</option>
                                        @foreach($orders as $order)
                                            @php
                                                $name = $order->guest_name ?? $order->user?->name ?? 'Guest';
                                                $total = number_format($order->total_amount, 0) . ' RWF';
                                            @endphp
                                            <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                                Order #{{ $order->id }} — {{ $name }} ({{ $total }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('order_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <label class="font-weight-bold small text-uppercase text-muted">Initial Logistics Status</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach(['pending' => 'Pending', 'shipped' => 'Shipped', 'in_transit' => 'In Transit'] as $val => $label)
                                            <div class="custom-control custom-radio custom-control-inline mr-3">
                                                <input type="radio" id="status_{{ $val }}" name="status" value="{{ $val }}" 
                                                       class="custom-control-input" {{ ($val == 'pending' || old('status') == $val) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status_{{ $val }}">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Carrier Details --}}
                    <div class="col-lg-5">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Shipping Carrier</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-muted">Carrier Name</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-truck text-muted"></i></span>
                                        </div>
                                        <input type="text" name="carrier" class="form-control @error('carrier') is-invalid @enderror" 
                                               value="{{ old('carrier') }}" placeholder="e.g. DHL, FedEx, MoMo Delivery">
                                    </div>
                                    @error('carrier') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-muted">Tracking Reference</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-barcode text-muted"></i></span>
                                        </div>
                                        <input type="text" name="tracking_number" class="form-control @error('tracking_number') is-invalid @enderror" 
                                               value="{{ old('tracking_number') }}" placeholder="Tracking ID (Optional)">
                                    </div>
                                    @error('tracking_number') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="alert alert-light border-0 small mb-0">
                                    <i class="fas fa-lightbulb text-warning mr-1"></i>
                                    If left empty, tracking can be added later during the "Shipped" stage.
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-block shadow-sm py-3 font-weight-bold">
                            <i class="fas fa-plus-circle mr-1"></i> Create Shipping Entry
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection