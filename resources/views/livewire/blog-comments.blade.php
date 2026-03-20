<div class="bg-white p-4 p-lg-5 shadow-sm rounded border-top border-primary mt-4">
    <h4 class="font-weight-bold mb-4 text-dark">Join the Conversation</h4>
    
    {{-- 1. Display Comments List --}}
    <div class="comments-container mb-5">
        @forelse($comments as $comment)
            {{-- wire:key is CRITICAL for Livewire to track items in a loop --}}
            <div class="media p-4 mb-3 bg-light rounded comment-bubble shadow-sm" wire:key="comment-{{ $comment->id }}">
                
                {{-- User Avatar / Initial --}}
                <div class="mr-3" style="flex-shrink:0;">
                    @if($comment->user && $comment->user->avatar)
                        <img src="{{ asset('storage/' . $comment->user->avatar) }}" class="rounded-circle border border-primary" 
                             style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-dark d-flex align-items-center justify-content-center font-weight-bold" 
                             style="width: 50px; height: 50px;">
                            {{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="media-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="font-weight-bold mb-0 text-dark">{{ $comment->user->name ?? 'Anonymous' }}</h6>
                        <small class="text-muted">
                            <i class="far fa-clock mr-1"></i> {{ $comment->created_at->diffForHumans() }}
                        </small>
                    </div>
                    
                    <p class="mb-2 text-secondary" style="line-height: 1.6;">{{ $comment->comment }}</p>

                    {{-- Actions: Only show Delete if authorized --}}
                    <div class="d-flex align-items-center justify-content-end">
                        @auth
                            @if(auth()->id() == $comment->user_id || auth()->user()->hasAnyRole(['admin', 'super admin']))
                                <button wire:click="deleteComment({{ $comment->id }})" 
                                        wire:confirm="Are you sure you want to delete this comment?"
                                        class="btn btn-link text-danger p-0 border-0 small text-decoration-none">
                                    <i class="fa fa-trash-alt mr-1"></i> Delete
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 border rounded bg-light border-dashed">
                <i class="fa fa-comments fa-3x text-muted mb-3 opacity-50"></i>
                <p class="text-muted font-italic mb-0">No comments yet. Be the first to start the discussion!</p>
            </div>
        @endforelse
    </div>

    <hr class="my-5">

    {{-- 2. Reply / Comment Form --}}
    <h5 class="font-weight-bold mb-3 text-dark">Leave a Reply</h5>
    
    @auth
        {{-- Success Message --}}
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="fa fa-check-circle mr-2"></i> {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form wire:submit.prevent="postComment">
            <div class="form-group position-relative">
                <textarea wire:model="newComment" 
                          class="form-control border-light shadow-sm @error('newComment') is-invalid @enderror" 
                          rows="4" 
                          placeholder="What are your thoughts on this article?"
                          style="resize: none; border-radius: 10px;"></textarea>
                
                @error('newComment') 
                    <span class="invalid-feedback font-weight-bold">{{ $message }}</span> 
                @enderror
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <p class="small text-muted mb-0">Logged in as <strong>{{ auth()->user()->name }}</strong></p>
                
                <button type="submit" 
                        class="btn btn-primary px-5 py-2 font-weight-bold shadow-sm text-dark hover-grow" 
                        wire:loading.attr="disabled">
                    {{-- Show spinner while processing --}}
                    <span wire:loading.remove wire:target="postComment">Submit Comment</span>
                    <span wire:loading wire:target="postComment">
                        <i class="fa fa-spinner fa-spin mr-2"></i> Posting...
                    </span>
                </button>
            </div>
        </form>
    @else
        <div class="alert alert-light border text-center py-5 shadow-sm rounded-lg">
            <i class="fa fa-lock fa-2x text-muted mb-3"></i>
            <p class="mb-3 text-muted">You must be logged in to join the conversation.</p>
            <a href="{{ route('login') }}" class="btn btn-primary px-4 font-weight-bold text-dark">Login Now</a>
        </div>
    @endauth
</div>