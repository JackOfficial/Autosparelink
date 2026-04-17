@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="mb-4">
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm mb-3">
            <i class="fa fa-arrow-left mr-1"></i> Back to Tickets
        </a>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="font-weight-bold">Ticket #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</h3>
                <p class="text-muted small">Subject: <span class="text-dark font-weight-bold">{{ $ticket->subject }}</span></p>
            </div>
            <div class="text-right">
                <span @class([
                    'badge px-4 py-2 rounded-pill shadow-sm mb-2 d-block',
                    'badge-success' => $ticket->status == 'open',
                    'badge-warning' => $ticket->status == 'pending',
                    'badge-secondary' => $ticket->status == 'closed',
                ])>
                    Status: {{ strtoupper($ticket->status) }}
                </span>
                <span class="badge badge-outline-dark border px-3 py-1 rounded-pill small">
                    Priority: {{ strtoupper($ticket->priority) }}
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-xl mb-4">
            <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Original Message --}}
            <div class="card border-0 shadow-sm rounded-xl mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                   <div class="d-flex align-items-center">
    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3 overflow-hidden" style="width: 45px; height: 45px; min-width: 45px;">
        @if($ticket->user->avatar)
            <img src="{{ $ticket->user->avatar }}" 
                 alt="{{ $ticket->user->name }}" 
                 style="width: 100%; height: 100%; object-fit: cover;">
        @else
            <span class="font-weight-bold">
                {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
            </span>
        @endif
    </div>
    
    <div>
        <h6 class="font-weight-bold mb-0">
            {{ $ticket->user->name }} 
            @if($ticket->user->shop)
                <span class="badge badge-info ml-1">Shop Vendor</span>
            @else
                <span class="badge badge-light border ml-1">Customer</span>
            @endif
        </h6>
        <small class="text-muted">{{ $ticket->created_at->format('M d, Y \a\t h:i A') }}</small>
    </div>
</div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="p-4 bg-light rounded-xl border-left-primary shadow-inner">
                        <p class="mb-0 text-dark" style="line-height: 1.6; white-space: pre-line;">{{ $ticket->message }}</p>
                    </div>

                    {{-- Attachments Gallery --}}
                    @if($ticket->photos->count() > 0)
                        <div class="mt-4">
                            <h6 class="small font-weight-bold text-muted text-uppercase mb-3">Attachments</h6>
                            <div class="row mx-n1">
                                @foreach($ticket->photos as $photo)
                                    <div class="col-3 px-1 mb-2">
                                        <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $photo->file_path) }}" class="img-fluid rounded border shadow-sm" style="height: 100px; width: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <h6 class="font-weight-bold mb-3 text-muted px-2">Conversation History</h6>

            @foreach($ticket->replies as $reply)
                <div @class([
                    'card border-0 shadow-sm rounded-xl mb-3',
                    'ml-5 border-right-primary shadow-sm bg-white' => $reply->user_id == auth()->id(),
                    'mr-5 bg-light border' => $reply->user_id != auth()->id()
                ])>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="font-weight-bold small {{ $reply->user_id == auth()->id() ? 'text-primary' : 'text-dark' }}">
                                {{ $reply->user->name }} 
                                @if($reply->user_id == auth()->id()) <small class="text-muted">(Support Admin)</small> @endif
                            </span>
                            <span class="text-muted small">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="small mb-0 text-dark" style="white-space: pre-line;">{{ $reply->message }}</p>
                    </div>
                </div>
            @endforeach

            {{-- Reply Form --}}
            <div class="card border-0 shadow-sm rounded-xl mt-4 bg-dark text-white">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold mb-3"><i class="fa fa-reply mr-2 text-primary"></i>Post a Official Reply</h6>
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <textarea name="message" rows="4" class="form-control rounded-xl border-0 p-3" 
                                      placeholder="Write your response here..." required></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                <i class="fa fa-paper-plane mr-2"></i> Send to {{ $ticket->user->shop ? 'Vendor' : 'User' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Quick Actions --}}
            <div class="card border-0 shadow-sm rounded-xl mb-4">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold mb-3 small text-uppercase text-muted">Update Status</h6>
                    <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-control rounded-pill bg-light border-0 mb-3">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <button type="submit" class="btn btn-dark btn-block rounded-pill shadow-sm">Update Ticket</button>
                    </form>
                </div>
            </div>

            {{-- Linked Order (Multivendor context) --}}
            @if($ticket->order)
            <div class="card border-0 shadow-sm rounded-xl mb-4 bg-primary text-white">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold mb-3 small text-uppercase">Linked Order</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Number:</span>
                        <span class="font-weight-bold">#{{ $ticket->order->order_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Grand Total:</span>
                        <span class="font-weight-bold">{{ number_format($ticket->order->grand_total) }} RWF</span>
                    </div>
                    <hr class="bg-white opacity-25">
                    <a href="{{ route('admin.orders.show', $ticket->order) }}" class="btn btn-sm btn-light btn-block rounded-pill">View Order Details</a>
                </div>
            </div>
            @endif

            {{-- User/Shop Contact Card --}}
           {{-- User/Shop Contact Card --}}
<div class="card border-0 shadow-sm rounded-xl">
    <div class="card-body p-4">
        <h6 class="font-weight-bold mb-3 text-muted small text-uppercase">
            {{ $ticket->user->shop ? 'Shop Information' : 'User Information' }}
        </h6>

        <div class="text-center mb-4">
            @if($ticket->user->shop && $ticket->user->shop->logo)
                {{-- Show Shop Logo --}}
                <img src="{{ asset('storage/' . $ticket->user->shop->logo) }}" 
                     alt="{{ $ticket->user->shop->name }}" 
                     class="img-fluid rounded-circle border shadow-sm mb-3" 
                     style="width: 80px; height: 80px; object-fit: cover;">
                <h5 class="font-weight-bold text-primary mb-0">{{ $ticket->user->shop->name }}</h5>
                <span class="badge badge-info-soft text-info px-3 py-1 rounded-pill small mt-2">Active Vendor</span>
            
            @elseif($ticket->user->avatar)
                {{-- Show User Avatar --}}
                <img src="{{ $ticket->user->avatar }}" 
                     alt="{{ $ticket->user->name }}" 
                     class="img-fluid rounded-circle border shadow-sm mb-3" 
                     style="width: 80px; height: 80px; object-fit: cover;">
                <h5 class="font-weight-bold mb-0">{{ $ticket->user->name }}</h5>
                <span class="badge badge-light border px-3 py-1 rounded-pill small mt-2">Customer</span>

            @else
                {{-- Fallback: Large Initials --}}
                <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" 
                     style="width: 80px; height: 80px; font-size: 24px; font-weight: bold;">
                    {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                </div>
                <h5 class="font-weight-bold mb-0">{{ $ticket->user->name }}</h5>
                <span class="badge badge-light border px-3 py-1 rounded-pill small mt-2">
                    {{ $ticket->user->shop ? 'Vendor' : 'Customer' }}
                </span>
            @endif
        </div>

        <hr class="my-4 opacity-50">

        <div class="mb-3">
            <small class="text-muted d-block mb-1">Contact Name</small>
            <span class="font-weight-bold">{{ $ticket->user->name }}</span>
        </div>
        
        <div class="mb-3">
            <small class="text-muted d-block mb-1">Email Address</small>
            <a href="mailto:{{ $ticket->user->email }}" class="font-weight-bold text-primary text-break">
                {{ $ticket->user->email }}
            </a>
        </div>

        @if($ticket->user->shop)
            <div class="mb-0 mt-3 p-3 bg-light rounded-lg">
                <small class="text-muted d-block mb-1">Shop Support Status</small>
                <span class="small font-weight-bold"><i class="fa fa-check-circle text-success mr-1"></i> Verified Merchant</span>
            </div>
        @endif
    </div>
</div>
        </div>
    </div>
</div>

<style>
    .rounded-xl { border-radius: 1rem !important; }
    .border-left-primary { border-left: 5px solid #007bff !important; }
    .border-right-primary { border-right: 5px solid #007bff !important; }
    .shadow-inner { box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.1); }
    .badge-info-soft { background-color: rgba(23, 162, 184, 0.1); }
    .rounded-lg { border-radius: 0.75rem; }
</style>
@endsection