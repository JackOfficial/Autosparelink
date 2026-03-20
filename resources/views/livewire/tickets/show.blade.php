<div wire:poll.15s class="container py-4 py-lg-5" style="overflow-x: hidden;" x-data="{ now: Date.now() }" x-init="setInterval(() => now = Date.now(), 10000)">
    {{-- Top Navigation & Status --}}
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <a href="{{ route('tickets.index') }}" class="text-decoration-none text-secondary d-inline-flex align-items-center fw-medium">
            <i class="fa fa-chevron-left me-2 small"></i> Back to Tickets
        </a>
        <div class="d-flex align-items-center">
            <span class="text-muted small me-2 text-uppercase fw-bold ls-1">Current Status:</span>
            <span class="badge {{ $ticket->status == 'closed' ? 'bg-secondary' : 'bg-success' }} px-3 py-2 rounded-pill shadow-sm border-0">
                {{ ucfirst($ticket->status) }}
            </span>
        </div>
    </div>

    {{-- Global Alerts --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
            <i class="fa fa-check-circle me-3 fs-5"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Initial Issue Card --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-light text-primary border mb-2 px-2 py-1 small fw-normal">Original Request</span>
                            <h4 class="h5 fw-bold text-dark mb-1">{{ $ticket->subject }}</h4>
                        </div>
                        <small class="text-muted bg-light px-2 py-1 rounded small">{{ $ticket->created_at->format('M d, H:i') }}</small>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="p-3 bg-light rounded-3 border-start border-primary border-4">
                        <p class="mb-0 text-dark opacity-85 lh-base">{{ $ticket->message }}</p>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 px-1">
                <hr class="flex-grow-1 opacity-10">
                <span class="mx-3 small fw-bold text-muted text-uppercase ls-1">Messages</span>
                <hr class="flex-grow-1 opacity-10">
            </div>

            {{-- Message History --}}
            <div id="chat-container" wire:ignore.self class="px-2 mb-4" style="max-height: 600px; overflow-y: auto; overflow-x: hidden; scroll-behavior: smooth;">
                @foreach($ticket->replies as $reply)
                    <div class="d-flex mb-4 {{ $reply->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}" 
                         wire:key="reply-{{ $reply->id }}">
                        
                        <div x-data="{ 
                                sentAt: {{ $reply->created_at->timestamp * 1000 }},
                                get canDelete() { return (now - this.sentAt) < (15 * 60 * 1000) }
                             }"
                             {{-- Increased padding-right for user messages to make room for button --}}
                             class="position-relative p-3 shadow-sm rounded-4 {{ $reply->user_id == auth()->id() ? 'bg-primary text-white ms-5' : 'bg-white border text-dark me-5' }}" 
                             style="min-width: 140px; max-width: 85%;">
                            
                            {{-- Floating Delete Action - Adjusted right: 0 to prevent overflow --}}
                            @if($reply->user_id == auth()->id())
                                <button x-show="canDelete" 
                                        x-transition 
                                        wire:click="deleteReply({{ $reply->id }})" 
                                        wire:confirm="Remove this message?"
                                        class="btn btn-sm btn-danger p-0 position-absolute shadow shadow-sm border-white border-2"
                                        style="top: -8px; right: -8px; width: 22px; height: 22px; border-radius: 50%; z-index: 10;"
                                        title="Delete">
                                    <i class="fa fa-times" style="font-size: 9px;"></i>
                                </button>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-bold {{ $reply->user_id == auth()->id() ? 'text-white' : 'text-primary' }}">
                                    {{ $reply->user_id == auth()->id() ? 'You' : 'Support Agent' }}
                                </span>
                                <span class="{{ $reply->user_id == auth()->id() ? 'text-white-50' : 'text-muted' }}" style="font-size: 11px;">
                                    {{ $reply->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>
                            
                            <p class="mb-0 fs-6 lh-sm">{{ $reply->message }}</p>

                            @if($reply->user_id == auth()->id())
                                <template x-if="canDelete">
                                    <div class="text-white-50 mt-2 d-flex align-items-center" style="font-size: 10px;">
                                        <i class="fa fa-clock-o me-1"></i>
                                        <span x-text="Math.ceil((15 * 60 * 1000 - (now - sentAt)) / 60000)"></span>m left to edit
                                    </div>
                                </template>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- New Reply Form --}}
            @if($ticket->status != 'closed')
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mt-2">
                    <div class="card-body p-0">
                        <form wire:submit.prevent="sendReply">
                            <textarea wire:model="message" rows="3" 
                                      class="form-control border-0 px-4 py-3 shadow-none @error('message') is-invalid @enderror" 
                                      placeholder="Write your message here..." 
                                      style="resize: none; border-bottom: 1px solid #f0f0f0 !important;"></textarea>
                            
                            <div class="bg-light px-4 py-3 d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    @error('message') <span class="text-danger"><i class="fa fa-warning"></i> Message is too short</span> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="sendReply">Send Reply <i class="fa fa-paper-plane ms-2"></i></span>
                                    <span wire:loading wire:target="sendReply"><i class="fa fa-spinner fa-spin"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
      <div class="col-lg-4">
    {{-- Main Sticky Wrapper --}}
    <div class="sticky-top" style="top: 2rem; z-index: 10;">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-4 d-flex align-items-center">
                    <i class="fa fa-info-circle text-primary me-2"></i> Ticket Details
                </h6>
                
                <div class="mb-3">
                    <label class="small text-muted text-uppercase fw-bold ls-1 mb-1">Order Reference</label>
                    <div class="fw-bold text-primary">{{ $ticket->order_ref ?? 'N/A' }}</div>
                </div>

                <div class="mb-3">
                    <label class="small text-muted text-uppercase fw-bold ls-1 mb-1">Department</label>
                    <div class="text-dark text-capitalize">{{ str_replace('_', ' ', $ticket->category) }}</div>
                </div>

                <div class="mb-0">
                    <label class="small text-muted text-uppercase fw-bold ls-1 mb-1">Priority Level</label>
                    <div>
                        <span class="badge {{ $ticket->priority == 'high' ? 'bg-danger-light text-danger' : 'bg-info-light text-info' }} rounded-pill border">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>
                </div>

                <hr class="my-4 opacity-5">

                <div class="d-grid gap-2">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill" onclick="window.print()">
                        <i class="fa fa-print me-2"></i> Print Transcript
                    </button>
                    
                    {{-- Quick Action: Close Ticket (Optional UI addition) --}}
                    @if($ticket->status !== 'closed')
                        <button wire:click="closeTicket" wire:confirm="Are you sure you want to close this ticket?" class="btn btn-light btn-sm rounded-pill text-danger border-0">
                            <i class="fa fa-lock me-2"></i> Close Ticket
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Optional: Add a second small card for Support Hours or FAQ below the sticky info --}}
        <div class="card border-0 shadow-sm rounded-4 mt-3 bg-primary text-white">
            <div class="card-body p-3 text-center">
                <small class="d-block opacity-75">Need urgent help?</small>
                <a href="https://wa.me/yournumber" class="text-white fw-bold text-decoration-none small">
                    <i class="fa fa-whatsapp"></i> Chat on WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
    </div>

    <style>
        .ls-1 { letter-spacing: 0.5px; }
        .bg-danger-light { background-color: #fff5f5; border-color: #feb2b2; }
        .bg-info-light { background-color: #f0f9ff; border-color: #bae6fd; }
        .rounded-4 { border-radius: 1rem !important; }
        #chat-container::-webkit-scrollbar { width: 5px; }
        #chat-container::-webkit-scrollbar-thumb { background: #dcdcdc; border-radius: 10px; }
    </style>

    @script
    <script>
        const chatContainer = document.getElementById('chat-container');
        const goToBottom = () => { if(chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight; };
        goToBottom();
        $wire.on('reply-sent', () => { setTimeout(goToBottom, 150); });
    </script>
    @endscript
</div>