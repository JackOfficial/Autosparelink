<div class="col-lg-3 d-none d-lg-block position-relative">
    <!-- Toggle Button -->
    <a class="btn d-flex align-items-center justify-content-between bg-primary w-100"
       data-toggle="collapse" href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
        <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Categories</h6>
        <i class="fa fa-angle-down text-dark"></i>
    </a>

    <!-- Vertical Navbar -->
    <nav class="collapse position-absolute navbar navbar-vertical navbar-light bg-light"
         id="navbar-vertical">
        <div class="navbar-nav w-100 navbar-vertical-scroll">

            @foreach($categories as $category)
                <div class="category-item">
                    <a href="#"
                       class="nav-link d-flex justify-content-between align-items-center"
                       onclick="event.preventDefault(); this.parentElement.classList.toggle('active'); 
                                this.nextElementSibling.classList.toggle('d-block');">
                        {{ $category->category_name }}
                        @if($category->children->count())
                            <i class="fas fa-chevron-down toggle-arrow"></i>
                        @endif
                    </a>

                    @if($category->children->count())
                        <div class="subcategory-list">
                            @foreach($category->children as $sub)
                                <a href="#" class="nav-link subcategory-link">{{ $sub->category_name }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
    </nav>
</div>
