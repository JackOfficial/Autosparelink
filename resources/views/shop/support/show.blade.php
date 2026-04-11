<x-shop-dashboard>
    <x-slot:title>Ticket: {{ $ticket->subject }}</x-slot:title>

    <div class="container-fluid py-4" x-data="{ sending: false }">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                
                {{-- Ticket Header & Original Message --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-2" style="font-size: 0.75rem;">
                                        <li class="breadcrumb-item"><a href="{{ route('shop.support.index') }}" class="text-decoration-none">Support</a></li>
                                        <li class="breadcrumb-item active">#ST-{{ $ticket->id }}</li>
                                    </ol>
                                </nav>
                                <h3 class="fw-bold text-dark mb-1">{{ $ticket->subject }}</h3>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-pill bg-light text-dark border small">{{ $ticket->category }}</span>
                                    <span class="text-muted small">Opened {{ $ticket->created_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                            </div>
                            <span @class([
                                'badge rounded-pill py-2 px-3',
                                'bg-success-subtle text-success' => $ticket->status == 'answered',
                                'bg-warning-subtle text-warning' => $ticket->status == 'pending',
                                'bg-secondary-subtle text-secondary' => $ticket->status == 'closed',
                            ])>
                                {{ strtoupper($ticket->status) }}
                            </span>
                        </div>
                        
                        <div class="bg-light p-3 rounded-3 mb-3">
                            <p class="text-dark mb-0" style="white-space: pre-wrap;">{{ $ticket->message }}</p>
                        </div>

                        {{-- Attachments from Original Ticket --}}
                        @if($ticket->photos->count() > 0)
                        <div class="d-flex gap-2 mt-3">
                            @foreach($ticket->photos as $photo)
                                <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Conversation Thread --}}
                <div class="conversation-thread mb-5">
                    <h6 class="text-muted text-uppercase small fw-bold mb-4 d-flex align-items-center">
                        <span class="me-2">Conversation</span>
                        <hr class="flex-grow-1">
                    </h6>

                    @forelse($ticket->replies as $reply)
                    <div class="mb-4 d-flex {{ $reply->isFromStaff() ? 'justify-content-start' : 'justify-content-end' }}">
                        <div @class([
                            'card border-0 shadow-sm',
                            'bg-white border-start border-primary border-4' => $reply->isFromStaff(),
                            'bg-primary text-white' => !$reply->isFromStaff()
                        ]) style="max-width: 85%; border-radius: 12px;">
                            <div class="card-body py-2 px-3">
                                <div @class(['small fw-bold mb-1', 'text-primary' => $reply->isFromStaff()])>
                                    {{ $reply->isFromStaff() ? '🛡️ Platform Support' : 'You' }}
                                </div>
                                <p class="mb-1" style="font-size: 0.95rem;">{{ $reply->message }}</p>
                                <div @class(['text-end small opacity-75', 'text-muted' => $reply->isFromStaff()]) style="font-size: 0.7rem;">
                                    {{ $reply->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="fas fa-clock me-1"></i> Waiting for a response from our team...
                    </div>
                    @endforelse
                </div>

                {{-- Reply Box --}}
                @if($ticket->status !== 'closed')
                <div class="card border-0 shadow-sm sticky-bottom mb-4" style="border-radius: 15px; bottom: 20px;">
                    <form action="{{ route('shop.support.reply', $ticket->id) }}" method="POST" @submit="sending = true">
                        @csrf
                        <div class="card-body p-3">
                            <textarea name="message" 
                                      class="form-control border-0 bg-light mb-2" 
                                      rows="3" 
                                      placeholder="Type your reply here..." 
                                      required></textarea>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i> Staff will be notified
                                </div>
                                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" x-bind:disabled="sending">
                                    <span x-show="!sending"><i class="fas fa-paper-plane me-2"></i>Send Reply</span>
                                    <span x-show="sending" x-cloak><span class="spinner-border spinner-border-sm me-2"></span>Sending...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @else
                <div class="alert alert-secondary text-center border-0 shadow-sm">
                    <i class="fas fa-lock me-2"></i> This ticket is closed and cannot be replied to.
                </div>
                @endif
            </div>
        </div>
    </div>
</x-shop-dashboard>