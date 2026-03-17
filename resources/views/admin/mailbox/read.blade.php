@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row">
        <div class="col-lg-3">
            <a href="{{ route('admin.mailbox.index') }}" class="btn btn-light btn-block mb-3 py-2 rounded-pill shadow-sm border">
                <i class="fa fa-arrow-left mr-2"></i> Back to Inbox
            </a>
            
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase font-weight-bold">Status Control</h6>
                    <form action="{{ route('admin.mailbox.status', $message->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <select name="status" class="form-control mb-3 rounded-pill border-light bg-light" onchange="this.form.submit()">
                            <option value="active" {{ $message->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="resolved" {{ $message->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="archived" {{ $message->status == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </form>
                    
                    <form action="{{ route('admin.mailbox.delete', $message->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-block rounded-pill btn-sm">
                            <i class="fa fa-trash mr-1"></i> Delete Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1 font-weight-bold text-dark">{{ $message->name ?? 'Guest User' }}</h4>
                            <p class="text-muted mb-0">
                                <i class="fa fa-envelope mr-1"></i> {{ $message->email }} 
                                @if($message->phone)
                                    <span class="mx-2">|</span> <i class="fa fa-phone mr-1"></i> {{ $message->phone }}
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-muted small">{{ $message->created_at->format('M d, Y h:i A') }}</span><br>
                            <span class="badge badge-pill {{ $message->status == 'active' ? 'badge-primary' : 'badge-success' }} px-3">
                                {{ ucfirst($message->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body border-top py-4">
                    <div class="message-body text-dark" style="white-space: pre-line; line-height: 1.6; font-size: 1.1rem;">
                        {{ $message->message }}
                    </div>
                </div>

                <div class="card-footer bg-light border-0 py-4 text-right" style="border-radius: 0 0 15px 15px;">
                    <a href="mailto:{{ $message->email }}?subject=Re: Inquiry from {{ config('app.name') }}" 
                       class="btn btn-primary px-5 py-2 rounded-pill shadow-sm">
                        <i class="fa fa-reply mr-2"></i> Reply via Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .message-body { color: #2d3436; }
    .btn-primary { transition: transform 0.2s; }
    .btn-primary:hover { transform: translateY(-2px); }
</style>
@endsection