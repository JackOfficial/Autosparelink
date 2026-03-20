<footer class="mt-5 pt-4 border-top">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        
        {{-- 1. Voting System --}}
        <div class="d-flex align-items-center mb-3">
            <span class="font-weight-bold mr-3 text-muted small text-uppercase tracking-wider">Was this helpful?</span>
            <div class="d-flex align-items-center bg-light px-2 py-1 rounded-pill shadow-sm border">
                
                {{-- Like Button --}}
                <button wire:click="toggleLike(true)" 
                    wire:loading.attr="disabled"
                    class="btn btn-sm btn-link transition-all d-flex align-items-center text-decoration-none mr-2 {{ $post->isLikedBy(auth()->id()) ? 'text-primary' : 'text-muted' }}">
                    <i class="fa{{ $post->isLikedBy(auth()->id()) ? 's' : 'r' }} fa-thumbs-up fa-lg"></i> 
                    <span class="font-weight-bold ml-2">{{ number_format($post->likes_count ?? 0) }}</span>
                </button>

                <div class="vr mx-1 text-light">|</div>

                {{-- Dislike Button --}}
                <button wire:click="toggleLike(false)" 
                    wire:loading.attr="disabled"
                    class="btn btn-sm btn-link transition-all d-flex align-items-center text-decoration-none {{ $post->isDislikedBy(auth()->id()) ? 'text-danger' : 'text-muted' }}">
                    <i class="fa{{ $post->isDislikedBy(auth()->id()) ? 's' : 'r' }} fa-thumbs-down fa-lg"></i> 
                    <span class="font-weight-bold ml-2">{{ number_format($post->dislikes_count ?? 0) }}</span>
                </button>
            </div>
            
            {{-- Precise Loading State --}}
            <div wire:loading wire:target="toggleLike" class="ml-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            </div>
        </div>

        {{-- 2. Social Sharing --}}
        <div class="d-flex align-items-center mb-3">
            <span class="font-weight-bold mr-3 text-muted small text-uppercase tracking-wider">Share:</span>
            
            <div class="share-group">
                <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" 
                   target="_blank" class="share-link whatsapp" title="Share on WhatsApp">
                   <i class="fab fa-whatsapp"></i>
                </a>

                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($post->title) }}" 
                   target="_blank" class="share-link x-twitter" title="Share on X">
                   <i class="fab fa-x-twitter"></i>
                </a>

                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
                   target="_blank" class="share-link facebook" title="Share on Facebook">
                   <i class="fab fa-facebook-f"></i>
                </a>
            </div>
        </div>
    </div>
</footer>