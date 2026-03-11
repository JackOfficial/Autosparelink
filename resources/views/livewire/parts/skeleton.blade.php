<div class="container-fluid px-xl-5 py-4">
    <div class="row">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm p-4 mb-4" style="height: 500px; background: #f8f9fa;">
                <div class="bg-light mb-3" style="height: 20px; width: 60%;"></div>
                <div class="bg-light mb-3" style="height: 40px;"></div>
                <div class="bg-light mb-3" style="height: 40px;"></div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="row">
                @for($i = 0; $i < 6; $i++)
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm" style="height: 300px;">
                            <div class="bg-light w-100" style="height: 200px;"></div>
                            <div class="p-3">
                                <div class="bg-light mb-2" style="height: 15px; width: 80%;"></div>
                                <div class="bg-light" style="height: 15px; width: 40%;"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>