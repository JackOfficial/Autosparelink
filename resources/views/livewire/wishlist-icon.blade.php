<a href="{{ route('wishlist.index') }}" class="text-white mr-3 position-relative" title="Wishlist">
    <i class="fas fa-heart fa-lg"></i>
    @if($count > 0)
        <span class="badge badge-primary position-absolute" style="top:-5px; right:-10px;">
            {{ $count }}
        </span>
    @endif
</a>