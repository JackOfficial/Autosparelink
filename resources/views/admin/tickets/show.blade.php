@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="mb-4">
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm mb-3">
            <i class="fa fa-arrow-left mr-1"></i> Back to Tickets
        </a>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="font-weight-bold">Ticket #TK-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</h3>
                <p class="text-muted small">{{ $ticket->subject }}</p>
            </div>
            <span class="badge {{ $ticket->status == 'closed' ? 'badge-secondary' : 'badge-success' }} px-4 py-2 rounded-pill shadow-sm">
                Status: {{ ucfirst($ticket->status) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-xl mb-4">
            <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-xl mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width: 45px; height: 45px;">
                            {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h6 class="font-weight-bold mb-0 small text-uppercase">{{ $ticket->user->name }} <span class="badge badge-light border ml-1">Customer</span></h6>
                            <small class="text-muted">{{ $ticket->created_at->format('M d, Y \a\t h:i A') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="p-4 bg-light rounded-xl border-left-primary" style="border-left: 4px solid #007bff;">
                        <p class="mb-0 text-dark" style="line-height: 1.6; white-space: pre-line;">
                            {{ $ticket->message }}
                        </p>
                    </div>
                </div>
            </div>

            <h6 class="font-weight-bold mb-3 text-muted px-2">Conversation History</h6>

            @foreach($ticket->replies as $reply)
                <div class="card border-0 shadow-sm rounded-xl mb-3 {{ $reply->user_id == auth()->id() ? 'ml-5 border-right-primary' : 'mr-5 bg-light' }}" 
                     style="{{ $reply->user_id == auth()->id() ? 'border-right: 4px solid #007bff;' : '' }}">
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

            <div class="card border-0 shadow-sm rounded-xl mt-4">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold mb-3">Post a Reply</h6>
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <textarea name="message" rows="4" class="form-control rounded-xl bg-light border-0 p-3" 
                                      placeholder="Explain the solution or ask for more details..." required></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                <i class="fa fa-paper-plane mr-2"></i> Send Response
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-xl mb-4">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold mb-3 small text-uppercase text-muted">Ticket Management</h6>
                    
                    <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label class="small font-weight-bold">Current Status</label>
                            <select name="status" class="form-control rounded-pill bg-light border-0">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending (Waiting for User)</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed / Resolved</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-dark btn-block rounded-pill shadow-sm mt-3">
                            Update Metadata
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold mb-3 text-muted small text-uppercase">Customer Contact</h6>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Full Name</small>
                        <span class="font-weight-bold">{{ $ticket->user->name }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Email Address</small>
                        <a href="mailto:{{ $ticket->user->email }}" class="font-weight-bold text-primary">{{ $ticket->user->email }}</a>
                    </div>
                    <hr>
                    <div class="text-center">
                        <small class="text-muted d-block mb-2">Member Since</small>
                        <span class="badge badge-light border px-3 py-2 rounded-pill">{{ $ticket->user->created_at->format('F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-xl { border-radius: 1.2rem !important; }
    .border-left-primary { border-left: 5px solid #007bff !important; }
    .border-right-primary { border-right: 5px solid #007bff !important; }
    .bg-light { background-color: #f8f9fa !important; }
</style>
@endsection