@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- 1. Header & Stats Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-dark text-white p-4" style="border-radius: 15px; background: linear-gradient(45deg, #1a1a1a, #333);">
                <div class="row align-items-center">
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mr-3" style="width: 60px; height: 60px; border: 3px solid rgba(255,255,255,0.1);">
                            <span class="h4 mb-0 text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h4 class="mb-0 font-weight-bold">Muraho, {{ explode(' ', $user->name)[0] }}!</h4>
                            <small class="opacity-75">Member since {{ $user->created_at->format('M Y') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-right mt-3 mt-md-0">
                        <div class="d-inline-block px-3 border-right border-secondary text-center">
                            <small class="d-block opacity-75 small uppercase">Total Spent</small>
                            <span class="h5 mb-0 font-weight-bold">{{ number_format($stats['total_spent']) }} RWF</span>
                        </div>
                        <div class="d-inline-block px-3 text-center">
                            <small class="d-block opacity-75 small uppercase">Orders</small>
                            <span class="h5 mb-0 font-weight-bold">{{ $stats['total_orders'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- 2. Sidebar --}}
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden mb-4">
                <div class="list-group list-group-flush small font-weight-bold">
                    <a href="#orders" class="list-group-item list-group-item-action py-3 d-flex align-items-center active">
                        <i class="fas fa-box-open mr-3"></i> My Orders
                        <span class="badge badge-pill badge-light ml-auto">{{ $stats['active_orders'] }}</span>
                    </a>
                    <a href="#garage" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                        <i class="fas fa-car mr-3 text-primary"></i> My Garage
                    </a>
                    <a href="#wishlist" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                        <i class="fas fa-heart mr-3 text-danger"></i> Wishlist
                    </a>
                    <a href="#cart" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                        <i class="fas fa-shopping-cart mr-3 text-success"></i> My Cart
                    </a>

                    <a href="#tickets" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
    <i class="fas fa-headset mr-3 text-warning"></i> Support Tickets
</a>

                    <a href="#" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                        <i class="fas fa-user-cog mr-3 text-muted"></i> Account Settings
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action py-3 d-flex align-items-center text-danger border-0 w-100">
                            <i class="fas fa-sign-out-alt mr-3"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body">
                    <h6 class="text-uppercase small font-weight-bold text-muted mb-3">Support</h6>
                    <p class="small text-muted">Need help with an order?</p>
                    <a href="https://wa.me/250xxxxxxx" class="btn btn-outline-success btn-sm btn-block rounded-pill">
                        <i class="fab fa-whatsapp mr-1"></i> WhatsApp Support
                    </a>
                </div>
            </div>
        </div>

        {{-- 3. Main Content --}}
        <div class="col-lg-9">

            {{-- New Section: Vehicle Garage --}}
            <div id="garage" class="card border-0 shadow-sm mb-5" style="border-radius: 15px; background: #f0f7ff;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="ml-3">
                            <h5 class="font-weight-bold mb-0 text-dark">My Garage</h5>
                            <p class="small text-muted mb-0">Parts shown on site will prioritize this vehicle.</p>
                        </div>
                    </div>

                    <form action="{{ route('garage.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="x-small font-weight-bold text-muted text-uppercase">Make</label>
                                <input type="text" name="make" class="form-control form-control-sm rounded-pill border-0 shadow-sm px-3" 
                                       placeholder="e.g. Toyota" value="{{ $user->primaryVehicle->make ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="x-small font-weight-bold text-muted text-uppercase">Model</label>
                                <input type="text" name="model" class="form-control form-control-sm rounded-pill border-0 shadow-sm px-3" 
                                       placeholder="e.g. RAV4" value="{{ $user->primaryVehicle->model ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="x-small font-weight-bold text-muted text-uppercase">Year</label>
                                <input type="number" name="year" class="form-control form-control-sm rounded-pill border-0 shadow-sm px-3" 
                                       placeholder="e.g. 2018" value="{{ $user->primaryVehicle->year ?? '' }}">
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm font-weight-bold">
                                Update Vehicle Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Recent Orders --}}
            <div id="orders" class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0">Track Recent Orders</h5>
                    <a href="#" class="small font-weight-bold text-primary">View All History</a>
                </div>

                @if($allOrders->isEmpty())
                    <div class="bg-white p-5 text-center rounded shadow-sm">
                        <img src="https://cdn-icons-png.flaticon.com/512/4555/4555971.png" style="width: 80px;" class="mb-3 opacity-50">
                        <p class="text-muted">No orders found. Ready to upgrade your vehicle?</p>
                        <a href="/parts" class="btn btn-primary rounded-pill px-4">Browse Parts</a>
                    </div>
                @else
                    <div class="accordion shadow-sm" id="ordersAccordion">
                        @foreach($allOrders as $order)
                            <div class="card border-0 mb-2 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                <div class="card-header bg-white border-bottom-0 py-3" id="orderHeading{{ $order->id }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <span class="text-muted small d-block">Order Reference</span>
                                            <span class="font-weight-bold text-dark">#{{ $order->id }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="text-muted small d-block">Status</span>
                                            @php
                                                $sStatus = $order->shipping->status ?? 'processing';
                                                $sBadge = match($sStatus) {
                                                    'shipped' => 'badge-info',
                                                    'delivered' => 'badge-success',
                                                    'cancelled' => 'badge-danger',
                                                    default => 'badge-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $sBadge }} px-3 rounded-pill">{{ ucfirst($sStatus) }}</span>
                                        </div>
                                        <div class="col-md-3 text-md-right">
                                            <span class="text-muted small d-block">Total Amount</span>
                                            <span class="font-weight-bold text-primary">{{ number_format($order->total_amount) }} RWF</span>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            @if($order->status == 'completed' && $order->payment)
                                                <a href="{{ route('payment.receipt', ['transactionId' => $order->payment->transaction_reference]) }}" 
                                                   class="btn btn-sm btn-outline-primary mr-1 border-0" title="Invoice">
                                                    <i class="fas fa-file-invoice fa-lg"></i>
                                                </a>
                                            @endif
                                            <button class="btn btn-sm btn-light border-0 px-3 rounded-pill" type="button" data-toggle="collapse" data-target="#orderCollapse{{ $order->id }}">
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="orderCollapse{{ $order->id }}" class="collapse" data-parent="#ordersAccordion">
                                    <div class="card-body bg-light rounded-bottom border-top">
                                        {{-- Shipping Progress --}}
                                        <div class="mb-4">
                                            <div class="progress mb-2" style="height: 6px; border-radius: 10px;">
                                                @php
                                                    $progress = match($sStatus) {
                                                        'pending' => 20,
                                                        'processing' => 40,
                                                        'shipped' => 70,
                                                        'delivered' => 100,
                                                        default => 10
                                                    };
                                                @endphp
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between small text-muted font-weight-bold">
                                                <span class="{{ $progress >= 20 ? 'text-primary' : '' }}">Ordered</span>
                                                <span class="{{ $progress >= 40 ? 'text-primary' : '' }}">Confirmed</span>
                                                <span class="{{ $progress >= 70 ? 'text-primary' : '' }}">On Road</span>
                                                <span class="{{ $progress >= 100 ? 'text-primary' : '' }}">Arrived</span>
                                            </div>
                                        </div>

                                        <div class="row small">
                                            <div class="col-md-6">
                                                <h6 class="font-weight-bold x-small text-uppercase text-muted">Shipping To</h6>
                                                <p class="mb-0">{{ $order->address->full_name ?? $user->name }}</p>
                                                <p class="text-muted">{{ $order->address->street_address ?? 'Standard Delivery' }}, {{ $order->address->city ?? '' }}</p>
                                            </div>
                                            <div class="col-md-6 text-md-right">
                                                <h6 class="font-weight-bold x-small text-uppercase text-muted">Items</h6>
                                                @foreach($order->orderItems as $item)
                                                    <div class="mb-1 text-dark">
                                                        {{ $item->part->part_name ?? 'Part' }} <span class="text-muted">(x{{ $item->quantity }})</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Wishlist & Cart Grid --}}
            <div class="row">
                <div class="col-md-6 mb-4" id="wishlist">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-weight-bold mb-0">Wishlist</h5>
                        <i class="fas fa-heart text-danger"></i>
                    </div>
                    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                        <ul class="list-group list-group-flush">
                            @forelse($wishlistItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <span class="small font-weight-bold">{{ $item->name }}</span>
                                    <span class="text-primary font-weight-bold small">{{ number_format($item->price) }} RWF</span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted text-center py-4 small">No saved parts</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 mb-4" id="cart">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-weight-bold mb-0">Active Cart</h5>
                        <i class="fas fa-shopping-basket text-success"></i>
                    </div>
                    <div class="card border-0 shadow-sm rounded-lg bg-dark text-white">
                        <div class="card-body">
                            @if($cartItems->isEmpty())
                                <p class="text-center opacity-50 py-3 mb-0 small">Your cart is empty</p>
                            @else
                                <div class="mb-3">
                                    @foreach($cartItems->take(3) as $item)
                                        <div class="d-flex justify-content-between mb-2 small">
                                            <span class="opacity-75">{{ Str::limit($item->name, 20) }}</span>
                                            <span>{{ number_format($item->price * $item->qty) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="border-top border-secondary pt-3 d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 font-weight-bold">{{ number_format($cartTotal) }} <small>RWF</small></h5>
                                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-sm rounded-pill px-3">Checkout</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Support Tickets Section --}}
<div id="tickets" class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="font-weight-bold mb-0">Support Tickets</h5>
        <button class="btn btn-warning btn-sm rounded-pill px-3 font-weight-bold" data-toggle="modal" data-target="#newTicketModal">
            <i class="fas fa-plus mr-1"></i> New Ticket
        </button>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 small">
                <thead class="bg-light text-muted uppercase x-small">
                    <tr>
                        <th class="border-0 px-4">Subject</th>
                        <th class="border-0">Status</th>
                        <th class="border-0">Last Update</th>
                        <th class="border-0 text-right px-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td class="px-4 align-middle">
                                <span class="font-weight-bold text-dark d-block">{{ $ticket->subject }}</span>
                                <span class="text-muted x-small">ID: #TK-{{ $ticket->id }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-pill {{ $ticket->status == 'open' ? 'badge-success' : 'badge-secondary' }} px-2">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="align-middle text-muted">{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td class="text-right px-4 align-middle">
                                <a href="#" class="btn btn-sm btn-light rounded-pill border">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No active tickets.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- New Ticket Modal --}}
<div class="modal fade" id="newTicketModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 border-radius-15">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="font-weight-bold">Open New Ticket</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">Subject</label>
                        <input type="text" name="subject" class="form-control bg-light border-0 rounded-pill" placeholder="e.g. Order #123 delayed" required>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">Message</label>
                        <textarea name="message" rows="4" class="form-control bg-light border-0" style="border-radius: 15px;" placeholder="Describe your issue..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="submit" class="btn btn-primary btn-block rounded-pill font-weight-bold">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

        </div>
    </div>
</div>

<style>
    .list-group-item.active { background-color: #f8f9fa; color: #007bff; border-color: transparent; border-left: 4px solid #007bff; }
    .x-small { font-size: 0.7rem; letter-spacing: 1px; }
    .progress-bar { transition: width 1.5s ease-in-out; }
</style>
@endsection