<div class="bg-white p-4 p-lg-5 shadow-sm rounded border-top border-primary mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold text-dark mb-0">Join the Conversation</h4>
        <span class="badge badge-primary px-3 py-2 text-dark font-weight-bold">{{ $comments->count() }} Comments</span>
    </div>
    
    {{-- 1. Display Comments List --}}
    <div class="comments-container mb-5">
        @forelse($comments as $comment)
            <div class="media p-4 mb-3 bg-white rounded comment-bubble shadow-sm transition-all border" 
                 wire:key="comment-{{ $comment->id }}"
                 style="border-left: 5px solid #007bff !important;">
                
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
                        <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 1.1rem;">{{ $comment->user->name ?? 'Anonymous' }}</h6>
                        <small class="text-muted font-weight-bold px-2 py-1 rounded border bg-light">
                            <i class="far fa-clock mr-1 text-primary"></i> {{ $comment->created_at->diffForHumans() }}
                        </small>
                    </div>
                    
                    <p class="mb-2 text-dark" style="line-height: 1.7; font-size: 1rem; color: #212529 !important;">
                        {{ $comment->comment }}
                    </p>

                    {{-- Actions Bar --}}
                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                        <div class="d-flex align-items-center">
                            {{-- Like Button --}}
                            <button wire:click="toggleCommentLike({{ $comment->id }}, true)" 
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm btn-link p-0 mr-3 text-decoration-none transition-all {{ $comment->isLikedBy(auth()->id()) ? 'text-primary' : 'text-dark' }}">
                                <i class="fa{{ $comment->isLikedBy(auth()->id()) ? 's' : 'r' }} fa-thumbs-up mr-1"></i> 
                                <span class="font-weight-bold">{{ $comment->likes()->where('is_like', true)->count() }}</span>
                            </button>

                            {{-- Dislike Button --}}
                            <button wire:click="toggleCommentLike({{ $comment->id }}, false)" 
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm btn-link p-0 mr-3 text-decoration-none transition-all {{ $comment->isDislikedBy(auth()->id()) ? 'text-danger' : 'text-dark' }}">
                                <i class="fa{{ $comment->isDislikedBy(auth()->id()) ? 's' : 'r' }} fa-thumbs-down mr-1"></i> 
                                <span class="font-weight-bold">{{ $comment->likes()->where('is_like', false)->count() }}</span>
                            </button>

                            {{-- Reply Button --}}
                            @auth
                                <button wire:click="setReply({{ $comment->id }})" 
                                        onclick="document.getElementById('comment-textarea').focus()"
                                        class="btn btn-sm btn-link text-primary p-0 border-0 text-decoration-none font-weight-bold">
                                    <i class="fa fa-reply mr-1"></i> Reply
                                </button>
                            @endauth
                        </div>

                        @auth
                            @if(auth()->id() == $comment->user_id || auth()->user()->hasAnyRole(['admin', 'super admin']))
                                <button wire:click="deleteComment({{ $comment->id }})" 
                                        wire:confirm="Permanent action: Are you sure you want to delete this comment?"
                                        class="btn btn-sm btn-link text-danger p-0 border-0 text-decoration-none opacity-75">
                                    <i class="fa fa-trash-alt mr-1"></i> Remove
                                </button>
                            @endif
                        @endauth
                    </div>

                    {{-- 1b. Display Nested Replies --}}
                    @if($comment->replies->count() > 0)
                        <div class="replies-container mt-3 pl-3 border-left" style="border-left: 2px solid #e9ecef !important;">
                            @foreach($comment->replies as $reply)
                                <div class="p-3 mb-2 bg-light rounded shadow-sm border" wire:key="reply-{{ $reply->id }}">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-dark small">{{ $reply->user->name ?? 'Anonymous' }}</strong>
                                        <span class="text-muted small" style="font-size: 0.7rem;">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mb-0 text-dark small" style="line-height: 1.5;">{{ $reply->comment }}</p>
                                    
                                    <div class="mt-2 d-flex align-items-center">
                                         <button wire:click="toggleCommentLike({{ $reply->id }}, true)" 
                                                class="btn btn-sm p-0 mr-3 {{ $reply->isLikedBy(auth()->id()) ? 'text-primary' : 'text-muted' }}" style="font-size: 0.75rem;">
                                            <i class="fa{{ $reply->isLikedBy(auth()->id()) ? 's' : 'r' }} fa-thumbs-up"></i> {{ $reply->likes()->where('is_like', true)->count() }}
                                        </button>
                                        @if(auth()->id() == $reply->user_id || auth()->user()->hasAnyRole(['admin', 'super admin']))
                                            <button wire:click="deleteComment({{ $reply->id }})" class="btn btn-sm text-danger p-0 border-0" style="font-size: 0.75rem; opacity: 0.6;">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5 border rounded bg-light border-dashed">
                <i class="fa fa-comments fa-3x text-muted mb-3 d-block" style="opacity: 0.3;"></i>
                <h6 class="text-dark font-weight-bold">No thoughts yet</h6>
                <p class="text-muted small">Be the first to share your perspective on this article.</p>
            </div>
        @endforelse
    </div>

    <hr class="my-5 border-light">

    {{-- 2. Reply/Comment Form --}}
    <div class="reply-section">
        {{-- Show Replying To Header --}}
        @if($replyingTo)
            <div class="alert alert-primary py-2 px-3 d-flex justify-content-between align-items-center mb-3 shadow-sm border-0" style="border-radius: 10px;">
                <span class="small font-weight-bold">
                    <i class="fa fa-reply mr-2"></i> Replying to {{ App\Models\Comment::find($replyingTo)->user->name }}
                </span>
                <button wire:click="cancelReply" class="btn btn-sm p-0 text-dark font-weight-bold" style="line-height: 1;">&times; Cancel</button>
            </div>
        @endif

        <h5 class="font-weight-bold mb-3 text-dark">{{ $replyingTo ? 'Write your reply' : 'Leave a Reply' }}</h5>
        
        @auth
            <form wire:submit.prevent="postComment">
                <div class="form-group mb-3">
                    <textarea id="comment-textarea"
                              wire:model="newComment" 
                              class="form-control shadow-sm text-dark @error('newComment') is-invalid @enderror" 
                              rows="4" 
                              placeholder="{{ $replyingTo ? 'Write your response...' : 'Add to the discussion...' }}"
                              style="resize: none; border-radius: 12px; background-color: #fff; border: 1px solid #ced4da; font-size: 1rem; color: #000 !important;"></textarea>
                    @error('newComment') <span class="invalid-feedback px-2">{{ $message }}</span> @enderror
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted font-weight-bold">
                        Posting as <span class="text-primary">{{ auth()->user()->name }}</span>
                    </div>
                    
                    <button type="submit" 
                            class="btn btn-primary px-4 py-2 font-weight-bold text-dark shadow hover-grow" 
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="postComment">{{ $replyingTo ? 'Post Reply' : 'Post Comment' }}</span>
                        <span wire:loading wire:target="postComment">
                            <i class="fa fa-circle-notch fa-spin mr-2"></i> Sending...
                        </span>
                    </button>
                </div>
            </form>
        @else
            <div class="card border-0 bg-light text-center py-4 rounded-lg">
                <p class="mb-3 text-dark font-weight-bold">Log in to join the conversation.</p>
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-4 text-dark font-weight-bold mx-auto">Sign In</a>
            </div>
        @endauth
    </div>
</div>