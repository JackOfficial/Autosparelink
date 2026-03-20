<div class="bg-white p-4 p-lg-5 shadow-sm rounded border-top border-primary mt-4"
     x-data="{ 
        scrolled: false,
        charLimit: 500,
        scrollToForm() {
            $refs.commentForm.scrollIntoView({ behavior: 'smooth' });
            $refs.commentArea.focus();
        },
        scrollTop() {
            $refs.scrollContainer.scrollTo({ top: 0, behavior: 'smooth' });
        }
     }">
    
    {{-- Header with Sorting --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 border-bottom pb-3">
        <div class="mb-3 mb-md-0">
            <h4 class="font-weight-bold text-dark mb-0">Join the Conversation</h4>
            <span class="badge badge-primary px-3 py-2 text-dark font-weight-bold mt-2">
                {{ $this->post->comments()->count() }} Comments
            </span>
        </div>
        
        <div class="d-flex align-items-center">
            <label class="mr-2 mb-0 small font-weight-bold text-muted">Sort by:</label>
            <select wire:model.live="sortBy" class="form-control form-control-sm shadow-sm border-0 bg-light text-dark font-weight-bold" style="width: 140px; border-radius: 8px; cursor: pointer;">
                <option value="latest">Newest</option>
                <option value="oldest">Oldest</option>
                <option value="popular">Most Liked</option>
            </select>
        </div>
    </div>
    
    {{-- 1. Display Comments List with Neutral Scrollbar --}}
    <div class="position-relative">
        <div class="comments-scroll-container mb-4 pr-2" 
             x-ref="scrollContainer"
             @scroll="scrolled = $el.scrollTop > 300"
             style="max-height: 600px; overflow-y: auto; scroll-behavior: smooth;">
            
            <style>
                /* Neutral Scrollbar Logic */
                .comments-scroll-container::-webkit-scrollbar { width: 6px; }
                .comments-scroll-container::-webkit-scrollbar-track { background: transparent; }
                .comments-scroll-container::-webkit-scrollbar-thumb { 
                    background: transparent; 
                    border-radius: 10px; 
                    transition: background 0.3s ease;
                }

                .comments-scroll-container:hover::-webkit-scrollbar-track { background: #f8f9fa; }
                .comments-scroll-container:hover::-webkit-scrollbar-thumb { background: #ccc; }
                .comments-scroll-container:hover::-webkit-scrollbar-thumb:hover { background: #999; }

                /* Firefox Compatibility */
                .comments-scroll-container { scrollbar-width: none; }
                .comments-scroll-container:hover {
                    scrollbar-width: thin;
                    scrollbar-color: #ccc transparent;
                }
            </style>

            <div class="comments-container">
                @forelse($comments as $comment)
                    <div class="media p-4 mb-3 bg-white rounded comment-bubble shadow-sm transition-all border" 
                         wire:key="comment-{{ $comment->id }}"
                         style="border-left: 5px solid #007bff !important;">
                        
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
                            
                            <p class="mb-2 text-dark" style="line-height: 1.7; font-size: 1rem;">
                                {{ $comment->comment }}
                            </p>

                            <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                                <div class="d-flex align-items-center">
                                    <button wire:click="toggleCommentLike({{ $comment->id }}, true)" 
                                            class="btn btn-sm btn-link p-0 mr-3 text-decoration-none transition-all {{ $comment->isLikedBy(auth()->id()) ? 'text-primary' : 'text-dark' }}">
                                        <i class="fa{{ $comment->isLikedBy(auth()->id()) ? 's' : 'r' }} fa-thumbs-up mr-1"></i> 
                                        <span class="font-weight-bold">{{ $comment->likes()->where('is_like', true)->count() }}</span>
                                    </button>

                                    @auth
                                        <button @click="scrollToForm(); $wire.setReply({{ $comment->id }})" 
                                                class="btn btn-sm btn-link text-primary p-0 border-0 text-decoration-none font-weight-bold">
                                            <i class="fa fa-reply mr-1"></i> Reply
                                        </button>
                                    @endauth
                                </div>
                            </div>

                            {{-- Nested Replies --}}
                            @if($comment->replies->count() > 0)
                                <div class="replies-container mt-3 pl-3 border-left" style="border-left: 2px solid #e9ecef !important;">
                                    @foreach($comment->replies as $reply)
                                        <div class="p-3 mb-2 bg-light rounded shadow-sm border" wire:key="reply-{{ $reply->id }}">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <strong class="text-dark small">{{ $reply->user->name ?? 'Anonymous' }}</strong>
                                                <span class="text-muted small" style="font-size: 0.7rem;">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="mb-0 text-dark small" style="line-height: 1.5;">{{ $reply->comment }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 border rounded bg-light border-dashed">
                        <i class="fa fa-comments fa-3x text-muted mb-3 d-block opacity-25"></i>
                        <h6 class="text-dark font-weight-bold">No thoughts yet</h6>
                    </div>
                @endforelse
            </div>

            @if($comments->hasMorePages())
                <div class="text-center my-4">
                    <button wire:click="$set('perPage', {{ $perPage + 10 }})" class="btn btn-outline-primary px-5 py-2 font-weight-bold shadow-sm" style="border-radius: 30px;">
                        <span wire:loading.remove wire:target="perPage">Load More</span>
                        <span wire:loading wire:target="perPage"><i class="fa fa-circle-notch fa-spin mr-2"></i> Loading...</span>
                    </button>
                </div>
            @endif
        </div>

        <button x-show="scrolled" x-transition @click="scrollTop()"
                class="btn btn-primary btn-sm rounded-circle position-absolute shadow" 
                style="bottom: 20px; right: 20px; width: 40px; height: 40px; z-index: 10;">
            <i class="fa fa-arrow-up text-dark"></i>
        </button>
    </div>

    <hr class="my-5 border-light">

    {{-- 3. Reply/Comment Form with Character Counter --}}
    <div class="reply-section" x-ref="commentForm">
        @if($replyingTo)
            <div class="alert alert-primary py-2 px-3 d-flex justify-content-between align-items-center mb-3 shadow-sm border-0" style="border-radius: 10px;">
                <span class="small font-weight-bold">
                    <i class="fa fa-reply mr-2"></i> Replying to {{ App\Models\Comment::find($replyingTo)->user->name }}
                </span>
                <button wire:click="cancelReply" class="btn btn-sm p-0 text-dark font-weight-bold">&times; Cancel</button>
            </div>
        @endif

        <h5 class="font-weight-bold mb-3 text-dark">{{ $replyingTo ? 'Write your reply' : 'Leave a Reply' }}</h5>
        
        @auth
            <form wire:submit.prevent="postComment">
                <div class="form-group mb-2">
                    <textarea x-ref="commentArea"
                              id="comment-textarea"
                              wire:model.live="newComment" 
                              class="form-control shadow-sm text-dark @error('newComment') is-invalid @enderror" 
                              rows="4" 
                              placeholder="Add to the discussion..."
                              style="resize: none; border-radius: 12px; background-color: #fff; border: 1px solid #ced4da; font-size: 1rem; color: #000 !important;"></textarea>
                    
                    {{-- Character Counter --}}
                    <div class="d-flex justify-content-end mt-1">
                        <small class="font-weight-bold" :class="$wire.newComment.length > charLimit ? 'text-danger' : 'text-muted'">
                            <span x-text="$wire.newComment.length"></span> / <span x-text="charLimit"></span> characters
                        </small>
                    </div>

                    @error('newComment') <span class="invalid-feedback px-2 d-block">{{ $message }}</span> @enderror
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted font-weight-bold">Posting as <span class="text-primary">{{ auth()->user()->name }}</span></div>
                    <button type="submit" 
                            class="btn btn-primary px-4 py-2 font-weight-bold text-dark shadow hover-grow"
                            :disabled="$wire.newComment.length > charLimit || $wire.newComment.length === 0">
                        <span wire:loading.remove wire:target="postComment">{{ $replyingTo ? 'Post Reply' : 'Post Comment' }}</span>
                        <span wire:loading wire:target="postComment"><i class="fa fa-circle-notch fa-spin"></i></span>
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