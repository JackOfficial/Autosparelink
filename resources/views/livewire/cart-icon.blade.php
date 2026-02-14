<a href="{{ route('cart.index') }}" class="text-white mr-3 position-relative" title="Cart">
    <i class="fas fa-shopping-cart fa-lg"></i>
    @if($count > 0)
        <span class="badge badge-primary position-absolute" style="top:-5px; right:-10px;">
            {{ $count }}
        </span>
    @endif
</a>