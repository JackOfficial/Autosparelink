<div class="ml-auto d-flex align-items-center">
                    <!-- Wishlist Icon -->
                    <a href="#" class="text-white mr-3 position-relative" title="Wishlist">
                        <i class="fas fa-heart fa-lg"></i>
                        <span class="badge badge-primary position-absolute" style="top:-5px; right:-10px;">
                            {{ $wishlistCount }}
                        </span>
                    </a>

                    <!-- Cart Icon -->
                    <a href="#" class="text-white mr-3 position-relative" title="Cart">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <span class="badge badge-primary position-absolute" style="top:-5px; right:-10px;">
                            {{ $cartCount }}
                        </span>
                    </a>

                    <!-- Authentication Links -->
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary btn-pill ml-2">Login / Register</a>
                    @else
                        <div class="dropdown">
                            <a href="#" class="btn btn-outline-primary btn-pill dropdown-toggle d-flex align-items-center" id="userDropdown" data-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg mr-2"></i> {{ Auth::user()->name }}
                            </a>
                           <div class="dropdown-menu dropdown-menu-right shadow-sm p-0" aria-labelledby="userDropdown" style="min-width: 200px; border-radius: 12px; overflow: hidden;">

    @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a class="dropdown-item d-flex align-items-center py-2 px-3" href="/admin">
            <i class="fas fa-cogs mr-2 text-primary"></i> Admin Panel
        </a>
    @else
        <a class="dropdown-item d-flex align-items-center py-2 px-3" href="/profile">
            <i class="fas fa-user mr-2 text-primary"></i> Profile
        </a>
    @endif

    <div class="dropdown-divider m-0"></div>

    <a class="dropdown-item d-flex align-items-center py-2 px-3" href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt mr-2 text-danger"></i> Logout
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>

                        </div>
                    @endguest
                </div>
