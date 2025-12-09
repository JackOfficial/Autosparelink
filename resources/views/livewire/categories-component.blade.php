<div class="col-lg-3 d-none d-lg-block">
    <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" 
       data-toggle="collapse" href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
        <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Categories</h6>
        <i class="fa fa-angle-down text-dark"></i>
    </a>

    <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light" 
         id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
        <div class="navbar-nav w-100">

            @foreach($categories as $category)
                @if($category->children->count())
                    <!-- Category with Subcategories -->
                    <div class="nav-item dropdown dropright">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            {{ $category->category_name }} 
                            <i class="fa fa-angle-right float-right mt-1"></i>
                        </a>
                        <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                            @foreach($category->children as $sub)
                                <a href="" class="dropdown-item">
                                    {{ $sub->category_name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Single Category -->
                    <a href="" class="nav-item nav-link">
                        {{ $category->category_name }}
                    </a>
                @endif
            @endforeach

        </div>
    </nav>
</div>
