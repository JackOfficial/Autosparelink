<div wire:poll.15s class="container py-5" x-data="{ now: Date.now() }" x-init="setInterval(() => now = Date.now(), 10000)">
    {{-- Header --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('tickets.index') }}" class="text-decoration-none text-muted">
            <i class="fa fa-arrow-left mr-1"></i> My Support Tickets
        </a>
        <span class="badge {{ $ticket->status == 'closed' ? 'bg-secondary' : 'bg-success' }} px-3 py-2 rounded-pill shadow-sm">
            Status: {{ ucfirst($ticket->status) }}
        </span>
    </div>

    {{-- Alerts --}}
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
            {{-- Initial Ticket --}}
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="h5 font-weight-bold mb-0 text-dark">{{ $ticket->subject }}</h4>
                        <small class="text-muted">{{ $ticket->created_at->format('M d, Y') }}</small>
                    </div>
                    <div class="p-3 bg-light rounded border-start border-primary border-4" style="border-left: 4px solid #007bff !important;">
                        <p class="mb-0 text-dark">{{ $ticket->message }}</p>
                    </div>
                </div>
            </div>

            <h6 class="font-weight-bold mb-3 text-muted px-2">Conversation</h6>

            {{-- Message History --}}
            <div id="chat-container" wire:ignore.self class="px-2 mb-4" style="max-height: 500px; overflow-y: auto; scroll-behavior: smooth;">
                @foreach($ticket->replies as $reply)
                    <div class="d-flex mb-4 {{ $reply->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}" 
                         wire:key="reply-{{ $reply->id }}">
                        
                        {{-- Alpine logic for 15-minute window --}}
                        <div x-data="{ 
                                sentAt: {{ $reply->created_at->timestamp * 1000 }},
                                get canDelete() { return (now - this.sentAt) < (15 * 60 * 1000) }
                             }"
                             class="position-relative p-3 shadow-sm rounded-lg {{ $reply->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-white border text-dark' }}" 
                             style="max-width: 80%;">
                            
                            {{-- Delete Button --}}
                            @if($reply->user_id == auth()->id())
                                <button x-show="canDelete" 
                                        x-transition:enter.duration.300ms
                                        x-transition:leave.duration.300ms
                                        wire:click="deleteReply({{ $reply->id }})" 
                                        wire:confirm="Permanently delete this message?"
                                        class="btn btn-sm btn-light p-0 position-absolute shadow-sm border"
                                        style="top: -12px; right: -12px; width: 24px; height: 24px; border-radius: 50%; color: #dc3545;"
                                        title="Delete within 15 mins">
                                    <i class="fa fa-times" style="font-size: 11px;"></i>
                                </button>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="fw-bold me-3">
                                    {{ $reply->user_id == auth()->id() ? 'You' : 'Support Team' }}
                                </small>
                                <small class="{{ $reply->user_id == auth()->id() ? 'text-white-50' : 'text-muted' }} small">
                                    {{ $reply->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <p class="mb-0 small">{{ $reply->message }}</p>

                            {{-- Optional: Tiny timer for deletion window --}}
                            @if($reply->user_id == auth()->id())
                                <div x-show="canDelete" class="text-white-50 mt-1" style="font-size: 9px;">
                                    <i class="fa fa-clock-o"></i> Editable for <span x-text="Math.ceil((15 * 60 * 1000 - (now - sentAt)) / 60000)"></span>m
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Reply Form --}}
            @if($ticket->status != 'closed')
                <div class="card border-0 shadow-sm rounded-lg mt-4">
                    <div class="card-body p-4">
                        <form wire:submit.prevent="sendReply">
                            <div class="mb-3">
                                <label class="small font-weight-bold text-uppercase text-muted">Your Response</label>
                                <textarea wire:model="message" rows="3" 
                                          class="form-control rounded-lg bg-light border-0 @error('message') is-invalid @enderror" 
                                          placeholder="Type your reply..." style="resize: none;"></textarea>
                                @error('message') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="sendReply">Send Message</span>
                                    <span wire:loading wire:target="sendReply">
                                        <i class="fa fa-spinner fa-spin me-1"></i> Sending...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary rounded-lg text-center mt-4 border-0 shadow-sm">
                    <i class="fa fa-lock me-2 text-muted"></i> This ticket is closed.
                </div>
            @endif
        </div>

        {{-- Sidebar Info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-lg sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold text-muted small text-uppercase mb-3">Ticket Information</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Ticket ID</span>
                            <span class="fw-bold small">#TK-{{ $ticket->id }}</span>
                        </div>
                        <div class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Category</span>
                            <span class="badge bg-light text-dark border font-weight-normal text-capitalize">{{ str_replace('_', ' ', $ticket->category) }}</span>
                        </div>
                        <div class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Priority</span>
                            <span class="fw-bold small text-capitalize {{ $ticket->priority == 'high' ? 'text-danger' : 'text-info' }}">
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
    const chatBox = document.getElementById('chat-container');
    const scrollDown = () => { if(chatBox) chatBox.scrollTop = chatBox.scrollHeight; };
    
    scrollDown(); // Initial

    $wire.on('reply-sent', () => {
        setTimeout(scrollDown, 100);
    });
</script>
@endscript