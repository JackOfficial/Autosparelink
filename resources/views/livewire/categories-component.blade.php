 <div class="dropdown-menu w-100 bg-light shadow-sm" aria-labelledby="categoriesDropdown">
                        <div class="container">
                            <div class="row py-3">
                                @foreach($categories as $category)
                                    <div class="col-lg-2 col-md-3 col-6">
                                        <h6 class="text-dark font-weight-bold">{{ $category->category_name }}</h6>
                                        <ul class="list-unstyled">
                                            @foreach($category->children as $sub)
                                                <li>
                                                    <a class="dropdown-item text-dark py-1" href="#">{{ $sub->category_name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>