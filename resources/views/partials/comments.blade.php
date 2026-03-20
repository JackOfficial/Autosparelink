<div class="bg-white p-4 p-lg-5 shadow-sm rounded border-top border-primary mt-4">
    <h4 class="font-weight-bold mb-4">Join the Conversation</h4>
    
    {{-- 1. Display Comments --}}
    <div class="comments-container mb-5">
        @forelse($comments as $comment)
            <div class="media p-4 mb-3 bg-light rounded comment-bubble shadow-sm" wire:key="comment-{{ $comment->id }}">
                {{-- User Avatar --}}
                <div class="mr-3" style="flex-shrink:0;">
                    @if($comment->user && $comment->user->avatar)
                        <img src="{{ $comment->user->avatar }}" class="rounded-circle border border-primary" 
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
                        <small class="text-muted"><i class="far fa-clock mr-1"></i> {{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    
                    <p class="mb-2 text-secondary">{{ $comment->comment }}</p>

                    <div class="d-flex align-items-center justify-content-end">
                        @auth
                            @if(auth()->id() == $comment->user_id || auth()->user()->hasAnyRole(['admin', 'super admin']))
                                <button wire:click="deleteComment({{ $comment->id }})" 
                                        wire:confirm="Delete this comment permanently?"
                                        class="btn btn-link text-danger p-0 border-0 small">
                                    <i class="fa fa-trash-alt mr-1"></i> Delete
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 border rounded bg-light">
                <i class="fa fa-comments fa-3x text-muted mb-3"></i>
                <p class="text-muted font-italic mb-0">No comments yet. Be the first to start the discussion!</p>
            </div>
        @endforelse
    </div>

    {{-- 2. Reply Form --}}
    <h5 class="font-weight-bold mb-3">Leave a Reply</h5>
    @auth
        <form wire:submit.prevent="postComment">
            <div class="form-group">
                <textarea wire:model="newComment" 
                          class="form-control border-light shadow-sm @error('newComment') is-invalid @enderror" 
                          rows="4" placeholder="Your thoughts..."></textarea>
                @error('newComment') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary px-5 py-2 font-weight-bold shadow-sm text-dark hover-grow" wire:loading.attr="disabled">
                <span wire:loading.remove>Submit Comment</span>
                <span wire:loading><i class="fa fa-spinner fa-spin"></i> Posting...</span>
            </button>
        </form>
    @else
        <div class="alert alert-light border text-center py-4 shadow-sm">
            <p class="mb-2 text-muted">You must be logged in to post a comment.</p>
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-4">Login Now</a>
        </div>
    @endauth
</div>