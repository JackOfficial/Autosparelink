<div class="bg-white p-4 p-lg-5 shadow-sm rounded border-top border-primary mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold text-dark mb-0">Join the Conversation</h4>
        <span class="badge badge-primary px-3 py-2 text-dark">{{ $comments->count() }} Comments</span>
    </div>
    
    {{-- 1. Display Comments List --}}
    <div class="comments-container mb-5">
        @forelse($comments as $comment)
            <div class="media p-4 mb-3 bg-light rounded comment-bubble shadow-sm transition-all" 
                 wire:key="comment-{{ $comment->id }}"
                 style="border-left: 4px solid transparent;">
                
                {{-- User Avatar --}}
                <div class="mr-3" style="flex-shrink:0;">
                    @if($comment->user && $comment->user->avatar)
                        <img src="{{ asset('storage/' . $comment->user->avatar) }}" 
                             class="rounded-circle border border-primary shadow-sm" 
                             style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-dark d-flex align-items-center justify-content-center font-weight-bold shadow-sm" 
                             style="width: 50px; height: 50px; font-size: 1.2rem;">
                            {{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="media-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="font-weight-bold mb-0 text-dark">{{ $comment->user->name ?? 'Anonymous' }}</h6>
                        <small class="text-muted bg-white px-2 py-1 rounded border">
                            <i class="far fa-clock mr-1 text-primary"></i> {{ $comment->created_at->diffForHumans() }}
                        </small>
                    </div>
                    
                    <p class="mb-2 text-secondary" style="line-height: 1.7; font-size: 0.95rem;">
                        {{ $comment->comment }}
                    </p>

                    {{-- Actions Bar --}}
                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                        <div class="d-flex align-items-center">
                            {{-- Like Button --}}
                            <button wire:click="toggleCommentLike({{ $comment->id }}, true)" 
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm btn-link p-0 mr-4 text-decoration-none transition-all {{ $comment->isLikedBy(auth()->id()) ? 'text-primary' : 'text-muted' }}">
                                <i class="fa{{ $comment->isLikedBy(auth()->id()) ? 's' : 'r' }} fa-thumbs-up mr-1"></i> 
                                <span class="font-weight-bold">{{ $comment->likes()->where('is_like', true)->count() }}</span>
                            </button>

                            {{-- Dislike Button --}}
                            <button wire:click="toggleCommentLike({{ $comment->id }}, false)" 
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm btn-link p-0 text-decoration-none transition-all {{ $comment->isDislikedBy(auth()->id()) ? 'text-danger' : 'text-muted' }}">
                                <i class="fa{{ $comment->isDislikedBy(auth()->id()) ? 's' : 'r' }} fa-thumbs-down mr-1"></i> 
                                <span class="font-weight-bold">{{ $comment->likes()->where('is_like', false)->count() }}</span>
                            </button>
                        </div>

                        @auth
                            @if(auth()->id() == $comment->user_id || auth()->user()->hasAnyRole(['admin', 'super admin']))
                                <button wire:click="deleteComment({{ $comment->id }})" 
                                        wire:confirm="Permanent action: Are you sure you want to delete this comment?"
                                        class="btn btn-sm btn-link text-danger p-0 border-0 text-decoration-none opacity-75 hover-opacity-100">
                                    <i class="fa fa-trash-alt mr-1"></i> Remove
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 border rounded bg-light border-dashed">
                <div class="mb-3">
                    <i class="fa fa-comments fa-3x text-muted opacity-25"></i>
                </div>
                <h6 class="text-dark font-weight-bold">No thoughts yet</h6>
                <p class="text-muted small">Be the first to share your perspective on this article.</p>
            </div>
        @endforelse
    </div>

    <hr class="my-5 border-light">

    {{-- 2. Reply Form --}}
    <div class="reply-section">
        <h5 class="font-weight-bold mb-3 text-dark">Leave a Reply</h5>
        
        @auth
            @if (session()->has('message'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="postComment">
                <div class="form-group mb-3">
                    <textarea wire:model="newComment" 
                              class="form-control border-light shadow-sm @error('newComment') is-invalid @enderror" 
                              rows="4" 
                              placeholder="Add to the discussion..."
                              style="resize: none; border-radius: 12px; background-color: #fcfcfc;"></textarea>
                    @error('newComment') <span class="invalid-feedback px-2">{{ $message }}</span> @enderror
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Posting as <span class="text-dark font-weight-bold">{{ auth()->user()->name }}</span>
                    </div>
                    
                    <button type="submit" 
                            class="btn btn-primary px-4 py-2 font-weight-bold text-dark shadow hover-grow" 
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="postComment">Post Comment</span>
                        <span wire:loading wire:target="postComment">
                            <i class="fa fa-circle-notch fa-spin mr-2"></i> Sending...
                        </span>
                    </button>
                </div>
            </form>
        @else
            <div class="card border-0 bg-light text-center py-4 rounded-lg">
                <p class="mb-3 text-muted">Log in to join the conversation and share your thoughts.</p>
                <div>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-4 font-weight-bold">Sign In</a>
                </div>
            </div>
        @endauth
    </div>
</div>