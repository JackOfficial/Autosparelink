<div wire:poll.15s class="container py-4 py-lg-5" style="overflow-x: hidden;" x-data="{ now: Date.now() }" x-init="setInterval(() => now = Date.now(), 10000)">
    
    {{-- Top Navigation & Status --}}
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <a href="{{ route('tickets.index') }}" class="text-decoration-none text-secondary d-inline-flex align-items-center font-weight-bold">
            <i class="fa fa-chevron-left mr-2 small"></i> Back to Tickets
        </a>
        <div class="d-flex align-items-center mt-3 mt-md-0">
            <span class="text-muted small mr-2 text-uppercase font-weight-bold ls-1">Current Status:</span>
            <span class="badge badge-pill {{ $ticket->status == 'closed' ? 'badge-secondary' : 'badge-success' }} px-3 py-2 shadow-sm border-0">
                {{ ucfirst($ticket->status) }}
            </span>
        </div>
    </div>

    {{-- Global Alerts --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-lg mb-4 d-flex align-items-center">
            <i class="fa fa-check-circle mr-3"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        {{-- Left Column: Messages --}}
        <div class="col-lg-8">
            {{-- Initial Issue Card --}}
            <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge badge-light text-primary border mb-2 px-2 py-1 font-weight-normal">Original Request</span>
                            <h4 class="h5 font-weight-bold text-dark mb-1">{{ $ticket->subject }}</h4>
                        </div>
                        <small class="text-muted bg-light px-2 py-1 rounded small">{{ $ticket->created_at->format('M d, H:i') }}</small>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="p-3 bg-light rounded-lg border-left border-primary" style="border-left-width: 4px !important;">
                        <p class="mb-0 text-dark opacity-85 lh-base">{{ $ticket->message }}</p>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 px-1">
                <hr class="flex-grow-1 opacity-10">
                <span class="mx-3 small font-weight-bold text-muted text-uppercase ls-1">Messages</span>
                <hr class="flex-grow-1 opacity-10">
            </div>

            {{-- Message History --}}
            <div id="chat-container" wire:ignore.self class="px-2 mb-4" style="max-height: 600px; overflow-y: auto; overflow-x: hidden; scroll-behavior: smooth;">
                @foreach($ticket->replies as $reply)
                    <div class="d-flex mb-4 {{ $reply->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}" wire:key="reply-{{ $reply->id }}">
                        
                        <div x-data="{ 
                                sentAt: {{ $reply->created_at->timestamp * 1000 }},
                                get canDelete() { return (now - this.sentAt) < (15 * 60 * 1000) }
                             }"
                             class="position-relative p-3 shadow-sm rounded-lg {{ $reply->user_id == auth()->id() ? 'bg-primary text-white ml-5' : 'bg-white border text-dark mr-5' }}" 
                             style="min-width: 140px; max-width: 85%;">
                            
                            {{-- Floating Delete Action --}}
                            @if($reply->user_id == auth()->id())
                                <button x-show="canDelete" x-transition wire:click="deleteReply({{ $reply->id }})" wire:confirm="Remove this message?"
                                        class="btn btn-sm btn-danger p-0 position-absolute shadow-sm"
                                        style="top: -10px; right: -10px; width: 24px; height: 24px; border-radius: 50%; z-index: 10; border: 2px solid white;">
                                    <i class="fa fa-times" style="font-size: 10px;"></i>
                                </button>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small font-weight-bold {{ $reply->user_id == auth()->id() ? 'text-white' : 'text-primary' }}">
                                    {{ $reply->user_id == auth()->id() ? 'You' : 'Support Agent' }}
                                </span>
                                <span class="{{ $reply->user_id == auth()->id() ? 'text-white-50' : 'text-muted' }}" style="font-size: 11px;">
                                    {{ $reply->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>
                            
                            <p class="mb-0 lh-sm" style="font-size: 0.95rem;">{{ $reply->message }}</p>

                            @if($reply->user_id == auth()->id())
                                <template x-if="canDelete">
                                    <div class="text-white-50 mt-2 d-flex align-items-center" style="font-size: 10px;">
                                        <i class="fa fa-clock-o mr-1"></i>
                                        <span x-text="Math.ceil((15 * 60 * 1000 - (now - sentAt)) / 60000)"></span>m left to delete
                                    </div>
                                </template>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- New Reply Form --}}
            @if($ticket->status != 'closed')
                <div class="card border-0 shadow-lg rounded-lg overflow-hidden mt-2">
                    <div class="card-body p-0">
                        <form wire:submit.prevent="sendReply">
                            <textarea wire:model.defer="message" rows="3" class="form-control border-0 px-4 py-3 shadow-none @error('message') is-invalid @enderror" 
                                      placeholder="Write your message here..." style="resize: none; border-bottom: 1px solid #f0f0f0 !important;"></textarea>
                            <div class="bg-light px-4 py-3 d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    @error('message') <span class="text-danger small"><i class="fa fa-warning mr-1"></i> Message is required</span> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold shadow-sm" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="sendReply">Send Reply <i class="fa fa-paper-plane ml-2"></i></span>
                                    <span wire:loading wire:target="sendReply"><i class="fa fa-spinner fa-spin"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Sticky Sidebar --}}
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="sticky-top" style="top: 2rem; z-index: 10;">
                <div class="card border-0 shadow-sm rounded-lg">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-dark mb-4 d-flex align-items-center">
                            <i class="fa fa-info-circle text-primary mr-2"></i> Ticket Details
                        </h6>
                        <div class="mb-3">
                            <label class="small text-muted text-uppercase font-weight-bold ls-1 mb-1">Order Ref</label>
                            <div class="font-weight-bold text-primary">#{{ $ticket->order->order_number ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted text-uppercase font-weight-bold ls-1 mb-1">Department</label>
                            <div class="text-dark text-capitalize small">{{ str_replace('_', ' ', $ticket->category) }}</div>
                        </div>
                        <div class="mb-4">
                            <label class="small text-muted text-uppercase font-weight-bold ls-1 mb-1">Priority</label>
                            <div>
                                <span class="badge badge-pill {{ $ticket->priority == 'high' ? 'badge-danger' : 'badge-info' }} border">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <button class="btn btn-outline-secondary btn-sm rounded-pill mb-2" onclick="window.print()">
                                <i class="fa fa-print mr-2"></i> Print Transcript
                            </button>
                            @if($ticket->status !== 'closed')
                                <button wire:click="closeTicket" wire:confirm="Close this ticket?" class="btn btn-light btn-sm rounded-pill text-danger border-0">
                                    <i class="fa fa-lock mr-2"></i> Close Ticket
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm rounded-lg mt-3 bg-primary text-white">
                    <div class="card-body p-3 text-center">
                        <small class="d-block opacity-75">Need urgent help?</small>
                        <a href="#" class="text-white font-weight-bold text-decoration-none small">
                            <i class="fa fa-whatsapp"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS Styles --}}
    <style>
        .ls-1 { letter-spacing: 0.5px; }
        .rounded-lg { border-radius: 1rem !important; }
        #chat-container::-webkit-scrollbar { width: 5px; }
        #chat-container::-webkit-scrollbar-thumb { background: #dcdcdc; border-radius: 10px; }
        .opacity-85 { opacity: 0.85; }
        .flex-grow-1 { flex-grow: 1; }
    </style>

    {{-- Script Section --}}
    <script>
        document.addEventListener('livewire:load', function () {
            const chatContainer = document.getElementById('chat-container');
            const goToBottom = () => { if(chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight; };
            
            goToBottom();

            window.livewire.on('reply-sent', () => {
                setTimeout(goToBottom, 150);
            });
        });
    </script>
</div>