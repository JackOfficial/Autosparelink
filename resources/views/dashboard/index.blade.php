@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- 1. Header & Global Stats Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg position-relative overflow-hidden" style="border-radius: 20px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                {{-- Decorative Glassmorphism Circle --}}
                <div class="position-absolute" style="top: -30px; right: -30px; width: 200px; height: 200px; background: rgba(59, 130, 246, 0.1); border-radius: 50%;"></div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-6 d-flex align-items-center mb-4 mb-md-0">
                            <div class="avatar-wrapper">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center shadow-lg" style="width: 75px; height: 75px; border: 4px solid rgba(255,255,255,0.1);">
                                    <span class="h3 mb-0 text-white font-weight-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="mb-1 font-weight-bold text-white">Muraho, {{ explode(' ', $user->name)[0] }}!</h3>
                                <p class="mb-0 text-white-50 small font-weight-medium">
                                    <i class="fas fa-certificate text-warning mr-1"></i> Premium Member since {{ $user->created_at->format('M Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row no-gutters bg-white bg-opacity-10 rounded-lg p-3 border border-white border-opacity-10" style="backdrop-filter: blur(10px);">
                                <div class="col-6 text-center border-right border-white border-opacity-10">
                                    <small class="d-block text-white-50 text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Total Investment</small>
                                    <span class="h5 mb-0 font-weight-bold text-white">{{ number_format($stats['total_spent']) }} <small>RWF</small></span>
                                </div>
                                <div class="col-6 text-center">
                                    <small class="d-block text-white-50 text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Orders Completed</small>
                                    <span class="h5 mb-0 font-weight-bold text-white">{{ $stats['total_orders'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- 2. Sidebar Navigation & Real-time Alerts --}}
        <div class="col-lg-3 mb-4">
            {{-- Navigation Menu --}}
            <div class="card border-0 shadow-sm rounded-xl overflow-hidden mb-4">
                <div class="list-group list-group-flush custom-sidebar-nav">
                    <a href="#orders" class="list-group-item list-group-item-action py-3 px-4 active d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-box-open mr-3"></i>My Orders</span>
                        <span class="badge badge-primary badge-pill">{{ $stats['active_orders'] }}</span>
                    </a>
                    <a href="#garage" class="list-group-item list-group-item-action py-3 px-4">
                        <i class="fas fa-car mr-3 text-info"></i>My Garage
                    </a>
                    <a href="#wishlist" class="list-group-item list-group-item-action py-3 px-4">
                        <i class="fas fa-heart mr-3 text-danger"></i>Wishlist
                    </a>
                    <a href="#tickets" class="list-group-item list-group-item-action py-3 px-4">
                        <i class="fas fa-headset mr-3 text-warning"></i>Support Tickets
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3 px-4">
                        <i class="fas fa-user-cog mr-3 text-secondary"></i>Account Settings
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0 border-top">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action py-3 px-4 text-danger border-0 w-100 text-left">
                            <i class="fas fa-sign-out-alt mr-3"></i>Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Integrated Notification Section --}}
            <div class="card border-0 shadow-sm rounded-xl mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="font-weight-bold mb-0">Notifications</h6>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <a href="{{ route('notifications.readAll') }}" class="x-small text-primary font-weight-bold">Clear</a>
                    @endif
                </div>
                <div class="card-body p-0" style="max-height: 280px; overflow-y: auto;">
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <div class="px-4 py-3 border-bottom hover-bg-light transition-3s">
                            <p class="mb-1 small font-weight-bold text-dark lh-12">{{ $notification->data['message'] }}</p>
                            <small class="text-muted x-small uppercase">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="p-4 text-center">
                            <p class="text-muted small mb-0">No new alerts</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <a href="https://wa.me/250xxxxxxx" class="btn btn-success btn-block rounded-pill shadow-sm py-2 font-weight-bold">
                <i class="fab fa-whatsapp mr-2"></i> WhatsApp Support
            </a>
        </div>

        {{-- 3. Main Dashboard Content --}}
        <div class="col-lg-9">
            
            {{-- My Garage Section --}}
            <div id="garage" class="card border-0 shadow-sm mb-4 rounded-xl" style="background: #f8fafc; border: 1px solid #e2e8f0 !important;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary p-2 rounded-lg text-white mr-3">
                            <i class="fas fa-car-side fa-fw"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold mb-0">Vehicle Garage</h5>
                            <p class="small text-muted mb-0">Personalize your experience for your specific car.</p>
                        </div>
                    </div>
                    <form action="{{ route('garage.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="x-small font-weight-bold text-uppercase text-muted ml-2">Make</label>
                                <input type="text" name="make" class="form-control custom-pill-input" placeholder="Toyota" value="{{ $user->primaryVehicle->make ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="x-small font-weight-bold text-uppercase text-muted ml-2">Model</label>
                                <input type="text" name="model" class="form-control custom-pill-input" placeholder="RAV4" value="{{ $user->primaryVehicle->model ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="x-small font-weight-bold text-uppercase text-muted ml-2">Year</label>
                                <input type="number" name="year" class="form-control custom-pill-input" placeholder="2024" value="{{ $user->primaryVehicle->year ?? '' }}">
                            </div>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-bold btn-sm">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Recent Orders Accordion --}}
            <div id="orders" class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0">Recent Activity</h5>
                    <a href="#" class="btn btn-link btn-sm text-primary font-weight-bold">Full History →</a>
                </div>

                @forelse($allOrders as $order)
                <div class="card border-0 shadow-sm mb-3 rounded-xl overflow-hidden">
                    <div class="card-header bg-white border-0 py-3 pointer" data-toggle="collapse" data-target="#ordCollapse{{ $order->id }}">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <span class="d-block x-small text-uppercase text-muted font-weight-bold">Order ID</span>
                                <span class="font-weight-bold text-dark">#REF-{{ $order->id }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="d-block x-small text-uppercase text-muted font-weight-bold">Status</span>
                                @php
                                    $sStatus = $order->shipping->status ?? 'pending';
                                    $statusClass = match($sStatus) { 'delivered' => 'bg-success-light text-success', 'shipped' => 'bg-info-light text-info', default => 'bg-warning-light text-warning' };
                                @endphp
                                <span class="badge badge-pill px-3 py-1 {{ $statusClass }} border-0">{{ ucfirst($sStatus) }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="d-block x-small text-uppercase text-muted font-weight-bold">Total Amount</span>
                                <span class="font-weight-bold text-primary">{{ number_format($order->total_amount) }} RWF</span>
                            </div>
                            <div class="col-md-3 text-right">
                                <i class="fas fa-chevron-down text-muted small"></i>
                            </div>
                        </div>
                    </div>
                    <div id="ordCollapse{{ $order->id }}" class="collapse">
                        <div class="card-body bg-light-blue border-top p-4">
                            {{-- Visual Stepper --}}
                            <div class="order-tracking mb-4">
                                @php
                                    $steps = ['pending' => 25, 'processing' => 50, 'shipped' => 75, 'delivered' => 100];
                                    $currentProgress = $steps[$sStatus] ?? 10;
                                @endphp
                                <div class="progress" style="height: 8px; border-radius: 10px; background: #e2e8f0;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: {{ $currentProgress }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 x-small font-weight-bold text-muted uppercase">
                                    <span class="{{ $currentProgress >= 25 ? 'text-primary' : '' }}">Received</span>
                                    <span class="{{ $currentProgress >= 50 ? 'text-primary' : '' }}">Processing</span>
                                    <span class="{{ $currentProgress >= 75 ? 'text-primary' : '' }}">In Transit</span>
                                    <span class="{{ $currentProgress >= 100 ? 'text-primary' : '' }}">Delivered</span>
                                </div>
                            </div>
                            
                            <div class="row small">
                                <div class="col-md-6 border-right">
                                    <h6 class="x-small font-weight-bold text-uppercase text-muted">Items Purchased</h6>
                                    @foreach($order->orderItems as $item)
                                        <div class="d-flex justify-content-between mb-1 pr-3">
                                            <span class="text-dark">{{ $item->part->part_name ?? 'Product' }} x{{ $item->quantity }}</span>
                                            <span class="font-weight-bold">{{ number_format($item->price * $item->quantity) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-6 pl-md-4">
                                    <h6 class="x-small font-weight-bold text-uppercase text-muted">Delivery Detail</h6>
                                    <p class="mb-0 text-dark font-weight-bold">{{ $order->address->full_name ?? $user->name }}</p>
                                    <p class="mb-0 text-muted">{{ $order->address->street_address ?? 'Kigali, Rwanda' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="card border-0 p-5 text-center shadow-sm rounded-xl bg-white">
                        <img src="https://cdn-icons-png.flaticon.com/512/11329/11329073.png" style="width: 80px;" class="mb-3 opacity-25 grayscale">
                        <p class="text-muted font-weight-bold mb-0">No orders placed yet.</p>
                    </div>
                @endforelse
            </div>

            {{-- Wishlist & Cart Grid --}}
            <div class="row">
                <div class="col-md-6 mb-4" id="wishlist">
                    <div class="card border-0 shadow-sm rounded-xl h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h6 class="font-weight-bold mb-0"><i class="fas fa-heart text-danger mr-2"></i>Wishlist</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($wishlistItems as $item)
                                    <li class="list-group-item d-flex justify-content-between py-3 px-4 border-0 hover-bg-light transition-3s">
                                        <span class="small font-weight-bold">{{ $item->name }}</span>
                                        <span class="text-primary font-weight-bold small">{{ number_format($item->price) }} RWF</span>
                                    </li>
                                @empty
                                    <li class="list-group-item border-0 text-muted text-center py-5 small">Your wishlist is empty</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4" id="cart">
                    <div class="card border-0 shadow-sm rounded-xl h-100 bg-dark text-white overflow-hidden position-relative">
                        <div class="card-body p-4 position-relative" style="z-index: 2;">
                            <h6 class="font-weight-bold mb-3"><i class="fas fa-shopping-basket text-success mr-2"></i>Active Cart</h6>
                            @if($cartItems->isEmpty())
                                <p class="text-center text-white-50 py-4 small">Ready to start shopping?</p>
                            @else
                                <div class="mb-4">
                                    @foreach($cartItems->take(3) as $item)
                                        <div class="d-flex justify-content-between mb-2 small text-white-50">
                                            <span>{{ Str::limit($item->name, 25) }}</span>
                                            <span class="text-white">{{ number_format($item->price * $item->qty) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between align-items-end pt-3 border-top border-white border-opacity-10">
                                    <div>
                                        <small class="text-white-50 d-block uppercase">Total</small>
                                        <h4 class="font-weight-bold mb-0 text-success">{{ number_format($cartTotal) }} <small>RWF</small></h4>
                                    </div>
                                    <a href="/checkout" class="btn btn-primary rounded-pill px-4 font-weight-bold shadow">Checkout</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

           {{-- Support Tickets Table --}}
<div id="tickets" class="card border-0 shadow-sm rounded-xl overflow-hidden">
    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="font-weight-bold mb-0">Help Desk</h5>
            <p class="small text-muted mb-0">Track your inquiries and part requests.</p>
        </div>
        <button class="btn btn-warning btn-sm rounded-pill px-3 font-weight-bold shadow-sm" data-toggle="modal" data-target="#newTicketModal">
            <i class="fas fa-plus mr-1"></i> New Ticket
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light text-muted uppercase x-small font-weight-bold">
                <tr>
                    <th class="border-0 px-4">Subject</th>
                    <th class="border-0 text-center">Status</th>
                    <th class="border-0 text-right px-4">Manage</th>
                </tr>
            </thead>
            <tbody class="small font-weight-medium">
                @forelse($tickets as $ticket)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="d-block text-dark font-weight-bold">{{ $ticket->subject }}</span>
                            <div class="d-flex align-items-center mt-1">
                                <span class="badge badge-light border x-small mr-2">{{ ucfirst($ticket->category) }}</span>
                                <span class="x-small text-muted">Opened {{ $ticket->created_at->format('d M, Y') }}</span>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            @php
                                $statusClasses = [
                                    'open' => 'bg-success-light text-success',
                                    'pending' => 'bg-warning-light text-warning',
                                    'closed' => 'bg-secondary text-white'
                                ];
                                $currentClass = $statusClasses[$ticket->status] ?? 'bg-light text-muted';
                            @endphp
                            <span class="badge badge-pill {{ $currentClass }} px-3 py-1 shadow-xs">
                                {{ strtoupper($ticket->status) }}
                            </span>
                        </td>
                        <td class="text-right px-4 align-middle">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 transition-3s">
                                <i class="fas fa-comments mr-1"></i> View Case
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            <i class="fas fa-ticket-alt fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0">No active support requests found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tickets->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
        </div>
    </div>
</div>

{{-- Global Scoped Styles --}}
<style>
    :root { --primary: #3b82f6; --dark: #0f172a; }
    .rounded-xl { border-radius: 1.2rem !important; }
    .transition-3s { transition: all 0.3s ease; }
    .hover-bg-light:hover { background-color: #f1f5f9; cursor: pointer; }
    .pointer { cursor: pointer; }
    .lh-12 { line-height: 1.2; }
    
    /* Sidebar Customization */
    .custom-sidebar-nav .list-group-item { border: none; font-size: 0.85rem; font-weight: 600; color: #64748b; margin-bottom: 2px; }
    .custom-sidebar-nav .list-group-item:hover { background-color: #f8fafc; color: var(--primary); }
    .custom-sidebar-nav .list-group-item.active { background-color: #eff6ff; color: var(--primary); border-left: 4px solid var(--primary); }
    
    /* Input Styling */
    .custom-pill-input { border-radius: 50px; background: #fff; border: 1.5px solid #e2e8f0; padding: 0.6rem 1.2rem; font-size: 0.85rem; transition: 0.2s; }
    .custom-pill-input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    
    /* Badges */
    .bg-success-light { background: #dcfce7; color: #166534; }
    .bg-info-light { background: #e0f2fe; color: #0369a1; }
    .bg-warning-light { background: #fef3c7; color: #92400e; }
    .bg-light-blue { background: #f8fafc; }
    .x-small { font-size: 0.65rem; letter-spacing: 0.5px; }
    
    .bg-opacity-10 { background-color: rgba(255, 255, 255, 0.1) !important; }
</style>

{{-- Re-inclusion of the Modal --}}
@include('partials.ticket-modal') 

@if($errors->has('subject') || $errors->has('message'))
    <script>
        $(document).ready(function() {
            $('#newTicketModal').modal('show');
        });
    </script>
@endif
@endsection