<footer class="mt-5 pt-4 border-top">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        
        {{-- 1. Voting System (Likes/Dislikes) --}}
        <div class="d-flex align-items-center mb-3">
            <span class="font-weight-bold mr-3 text-muted small text-uppercase">Was this helpful?</span>
            <div class="d-flex align-items-center bg-light px-3 py-2 rounded-pill shadow-sm">
                
                {{-- Like Form --}}
                <form action="{{ route('like.toggle') }}" method="POST" class="mr-3">
                    @csrf
                    <input type="hidden" name="id" value="{{ $post->id }}">
                    <input type="hidden" name="type" value="blog">
                    <input type="hidden" name="vote" value="like">
                    <button type="submit" class="vote-btn {{ $post->likes()->where(['user_id' => auth()->id(), 'is_like' => true])->exists() ? 'text-primary' : 'text-muted' }}">
                        <i class="fa fa-thumbs-up fa-lg"></i> 
                        <small class="font-weight-bold ml-1">{{ $post->likes()->where('is_like', true)->count() }}</small>
                    </button>
                </form>

                {{-- Dislike Form --}}
                <form action="{{ route('like.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $post->id }}">
                    <input type="hidden" name="type" value="blog">
                    <input type="hidden" name="vote" value="dislike">
                    <button type="submit" class="vote-btn {{ $post->likes()->where(['user_id' => auth()->id(), 'is_like' => false])->exists() ? 'text-danger' : 'text-muted' }}">
                        <i class="fa fa-thumbs-down fa-lg"></i> 
                        <small class="font-weight-bold ml-1">{{ $post->likes()->where('is_like', false)->count() }}</small>
                    </button>
                </form>
            </div>
        </div>

        {{-- 2. Social Sharing --}}
        <div class="mb-3">
            <span class="font-weight-bold mr-3 text-muted small text-uppercase">Share:</span>
            
            {{-- WhatsApp --}}
            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" 
               target="_blank" class="share-btn btn btn-success rounded-circle mr-2">
               <i class="fab fa-whatsapp"></i>
            </a>

            {{-- Twitter / X --}}
            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($post->title) }}" 
               target="_blank" class="share-btn btn btn-dark rounded-circle mr-2">
               <i class="fab fa-x-twitter"></i>
            </a>

            {{-- Facebook --}}
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
               target="_blank" class="share-btn btn btn-primary rounded-circle">
               <i class="fab fa-facebook-f"></i>
            </a>
        </div>
    </div>
</footer>