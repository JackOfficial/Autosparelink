<div wire:poll.15s="ticketProperty" class="container py-5">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('tickets.index') }}" class="text-decoration-none text-muted">
            <i class="fa fa-arrow-left mr-1"></i> My Support Tickets
        </a>
        <span class="badge {{ $ticket->status == 'closed' ? 'badge-secondary' : 'badge-success' }} px-3 py-2 rounded-pill shadow-sm">
            Status: {{ ucfirst($ticket->status) }}
        </span>
    </div>

    {{-- Success/Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-lg mb-4">
            <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-lg mb-4">
            <i class="fa fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Ticket Subject & Initial Message --}}
            <div class="card border-0 shadow-sm rounded-xl mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="font-weight-bold mb-0 text-dark">{{ $ticket->subject }}</h4>
                        <small class="text-muted text-nowrap ml-3">{{ $ticket->created_at->format('M d, Y') }}</small>
                    </div>
                    <div class="p-3 bg-light rounded-lg border-left" style="border-left: 4px solid #007bff !important;">
                        <p class="mb-0 text-dark">{{ $ticket->message }}</p>
                    </div>
                </div>
            </div>

            <h6 class="font-weight-bold mb-3 text-muted px-2">Messages</h6>

            {{-- Message History Container --}}
            <div id="chat-container" class="px-2" style="max-height: 500px; overflow-y: auto; scroll-behavior: smooth;">
                @foreach($ticket->replies as $reply)
                    <div class="d-flex mb-3 {{ $reply->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}" wire:key="reply-{{ $reply->id }}">
                        <div class="p-3 shadow-sm rounded-lg {{ $reply->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-white border' }}" style="max-width: 85%;">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="font-weight-bold mr-4">
                                    {{ $reply->user_id == auth()->id() ? 'You' : 'Support Agent' }}
                                </small>
                                <small class="{{ $reply->user_id == auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                    {{ $reply->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <p class="mb-0 small">{{ $reply->message }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Reply Section --}}
            @if($ticket->status != 'closed')
                <div class="card border-0 shadow-sm rounded-xl mt-4">
                    <div class="card-body p-4">
                        <form wire:submit.prevent="sendReply">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-uppercase text-muted">Your Response</label>
                                <textarea 
                                    wire:model="message" 
                                    rows="3" 
                                    class="form-control rounded-lg bg-light border-0 @error('message') is-invalid @enderror" 
                                    placeholder="Describe your issue or provide an update..."
                                    style="resize: none;"></textarea>
                                @error('message') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="sendReply">Send Message</span>
                                    <span wire:loading wire:target="sendReply">
                                        <i class="fa fa-spinner fa-spin mr-1"></i> Sending...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary rounded-lg text-center mt-4 border-0 shadow-sm">
                    <i class="fa fa-lock mr-2 text-muted"></i> This ticket is closed. Please open a new ticket for further assistance.
                </div>
            @endif
        </div>

        {{-- Sidebar Info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-xl position-sticky" style="top: 20px;">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold text-muted small text-uppercase mb-3">Ticket Information</h6>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Ticket ID</span>
                            <span class="font-weight-bold small">#TK-{{ $ticket->id }}</span>
                        </div>
                        <div class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Category</span>
                            <span class="badge badge-light border font-weight-normal text-capitalize">{{ str_replace('_', ' ', $ticket->category) }}</span>
                        </div>
                        @if($ticket->order_ref)
                            <div class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Order Ref</span>
                                <span class="font-weight-bold small text-primary">{{ $ticket->order_ref }}</span>
                            </div>
                        @endif
                        <div class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Priority</span>
                            <span class="text-{{ $ticket->priority == 'high' ? 'danger' : ($ticket->priority == 'medium' ? 'warning' : 'info') }} font-weight-bold small text-capitalize">
                                {{ $ticket->priority }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    const scrollToBottom = () => {
        const container = document.getElementById('chat-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    };

    // Initial scroll
    scrollToBottom();

    // Event listener for new replies
    $wire.on('reply-sent', () => {
        setTimeout(scrollToBottom, 50);
    });
</script>
@endscript