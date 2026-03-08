@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                <div class="card-body text-center bg-primary text-white py-4">
                    <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <span class="h2 mb-0 text-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <h5 class="mb-0">{{ $user->name }}</h5>
                    <small class="opacity-75">Member since {{ $user->created_at->format('M Y') }}</small>
                </div>
                <div class="list-group list-group-flush">
                    <a href="#orders" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-box mr-3 text-muted"></i> My Orders
                    </a>
                    <a href="#wishlist" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-heart mr-3 text-muted"></i> Wishlist
                    </a>
                    <a href="#cart" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-shopping-cart mr-3 text-muted"></i> My Cart
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center text-danger">
                        <i class="fas fa-sign-out-alt mr-3"></i> Logout
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-lg mt-4">
                <div class="card-body">
                    <h6 class="text-uppercase small font-weight-bold text-muted mb-3">Profile Info</h6>
                    <p class="mb-1 small"><strong>Email:</strong></p>
                    <p class="text-muted small">{{ $user->email }}</p>
                    <button class="btn btn-outline-primary btn-sm btn-block">Edit Profile</button>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div id="orders" class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="font-weight-bold">Recent Orders</h4>
                    <span class="badge badge-pill badge-light border">{{ $orders->count() }} Orders</span>
                </div>

                @if($orders->isEmpty())
                    <div class="bg-light p-5 text-center rounded border">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">You haven't placed any orders yet.</p>
                        <a href="/spare-parts" class="btn btn-primary">Start Shopping</a>
                    </div>
                @else
                    <div class="accordion shadow-sm" id="ordersAccordion">
                        @foreach($orders as $order)
                            <div class="card border-0 mb-2">
                                <div class="card-header bg-white border-bottom-0 py-3" id="orderHeading{{ $order->id }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <span class="text-muted small">Order ID</span>
                                            <div class="font-weight-bold">#{{ $order->id }}</div>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="text-muted small">Status</span>
                                            <div>
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'badge-warning',
                                                        'delivered' => 'badge-success',
                                                        'shipped' => 'badge-info',
                                                        'cancelled' => 'badge-danger'
                                                    ][$order->status] ?? 'badge-secondary';
                                                @endphp
                                                <span class="badge {{ $statusClass }} px-3">{{ ucfirst($order->status) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-md-right">
                                            <span class="text-muted small">Total</span>
                                            <div class="font-weight-bold text-primary">{{ number_format($order->total_amount) }} RWF</div>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <button class="btn btn-sm btn-light border px-3" type="button" data-toggle="collapse" data-target="#orderCollapse{{ $order->id }}">
                                                Details <i class="fas fa-chevron-down ml-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="orderCollapse{{ $order->id }}" class="collapse" data-parent="#ordersAccordion">
                                    <div class="card-body bg-light rounded-bottom border-top">
                                        <div class="row">
                                            <div class="col-md-6 border-right">
                                                <h6 class="font-weight-bold"><i class="fas fa-map-marker-alt mr-2 text-primary"></i> Shipping</h6>
                                                <p class="small text-muted">
                                                    {{ $order->address->full_name }}<br>
                                                    {{ $order->address->street_address }}, {{ $order->address->city }}<br>
                                                    Tracking: <span class="text-dark">{{ $order->shipping->tracking_number ?? 'N/A' }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="font-weight-bold"><i class="fas fa-credit-card mr-2 text-primary"></i> Payment</h6>
                                                <p class="small text-muted">
                                                    Method: {{ strtoupper($order->payment->method) }}<br>
                                                    Status: <span class="text-success">{{ ucfirst($order->payment->status) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                        <h6 class="font-weight-bold mb-3">Items</h6>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($order->orderItems as $item)
                                                <li class="d-flex justify-content-between mb-2 small">
                                                    <span>{{ $item->part->part_name }} <strong>(x{{ $item->quantity }})</strong></span>
                                                    <span>{{ number_format($item->unit_price * $item->quantity) }} RWF</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-6 mb-4" id="wishlist">
                    <h5 class="font-weight-bold mb-3"><i class="fas fa-heart text-danger mr-2"></i> Wishlist</h5>
                    <div class="card border-0 shadow-sm rounded-lg h-100">
                        <ul class="list-group list-group-flush">
                            @forelse($wishlistItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <span class="small font-weight-bold">{{ $item->name }}</span>
                                    <span class="badge badge-light text-primary border">{{ number_format($item->price) }} RWF</span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted text-center py-4">Wishlist is empty</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 mb-4" id="cart">
                    <h5 class="font-weight-bold mb-3"><i class="fas fa-shopping-cart text-success mr-2"></i> Active Cart</h5>
                    <div class="card border-0 shadow-sm rounded-lg h-100 bg-dark text-white">
                        <div class="card-body">
                            @if($cartItems->isEmpty())
                                <p class="text-center opacity-75 py-4">Cart is empty</p>
                            @else
                                <div class="mb-4">
                                    @foreach($cartItems as $item)
                                        <div class="d-flex justify-content-between mb-2 small">
                                            <span>{{ $item->name }} (x{{ $item->qty }})</span>
                                            <span>{{ number_format($item->price * $item->qty) }} RWF</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="border-top border-secondary pt-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="opacity-75 d-block">Total Amount</small>
                                        <h5 class="mb-0 font-weight-bold">{{ number_format($cartTotal) }} RWF</h5>
                                    </div>
                                    <a href="{{ route('checkout') }}" class="btn btn-primary shadow-sm">Checkout <i class="fas fa-arrow-right ml-1"></i></a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection