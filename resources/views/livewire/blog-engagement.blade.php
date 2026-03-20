<footer class="mt-5 pt-4 border-top">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        
        {{-- 1. Voting System (Likes/Dislikes) --}}
        <div class="d-flex align-items-center mb-3">
            <span class="font-weight-bold mr-3 text-muted small text-uppercase">Was this helpful?</span>
            <div class="d-flex align-items-center bg-light px-3 py-2 rounded-pill shadow-sm">
                
                {{-- Like Button --}}
                <button wire:click="toggleLike(true)" 
                    class="vote-btn mr-3 border-0 bg-transparent {{ $post->likes()->where(['user_id' => auth()->id(), 'is_like' => true])->exists() ? 'text-primary' : 'text-muted' }}"
                    wire:loading.attr="disabled">
                    <i class="fa fa-thumbs-up fa-lg"></i> 
                    <small class="font-weight-bold ml-1">{{ $post->likes()->where('is_like', true)->count() }}</small>
                </button>

                {{-- Dislike Button --}}
                <button wire:click="toggleLike(false)" 
                    class="vote-btn border-0 bg-transparent {{ $post->likes()->where(['user_id' => auth()->id(), 'is_like' => false])->exists() ? 'text-danger' : 'text-muted' }}"
                    wire:loading.attr="disabled">
                    <i class="fa fa-thumbs-down fa-lg"></i> 
                    <small class="font-weight-bold ml-1">{{ $post->likes()->where('is_like', false)->count() }}</small>
                </button>
            </div>
            
            {{-- Optional: Loading Spinner --}}
            <div wire:loading class="ml-2 small text-muted">
                <i class="fa fa-spinner fa-spin"></i>
            </div>
        </div>

        {{-- 2. Social Sharing (Keep as standard links, they don't need Livewire) --}}
        <div class="mb-3">
            <span class="font-weight-bold mr-3 text-muted small text-uppercase">Share:</span>
            
            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" 
               target="_blank" class="share-btn btn btn-success rounded-circle mr-2">
               <i class="fab fa-whatsapp"></i>
            </a>

            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($post->title) }}" 
               target="_blank" class="share-btn btn btn-dark rounded-circle mr-2">
               <i class="fab fa-x-twitter"></i>
            </a>

            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
               target="_blank" class="share-btn btn btn-primary rounded-circle">
               <i class="fab fa-facebook-f"></i>
            </a>
        </div>
    </div>
</footer>