<div class="container-fluid px-xl-5 py-4">
    <style>
        .skeleton-shimmer {
            background: #eee;
            background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
            border-radius: 5px;
            background-size: 200% 100%;
            animation: 1.5s shine linear infinite;
        }

        @keyframes shine {
            to {
                background-position-x: -200%;
            }
        }

        .skeleton-card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            background: white;
        }
    </style>

    <div class="row">
        {{-- 1. Sidebar Skeleton --}}
        <div class="col-lg-3">
            <div class="card skeleton-card shadow-sm p-4 mb-4">
                <div class="skeleton-shimmer mb-4" style="height: 30px; width: 70%;"></div>
                
                <div class="skeleton-shimmer mb-3" style="height: 45px; border-radius: 50px;"></div> {{-- Search Bar --}}
                
                <div class="skeleton-shimmer mb-2" style="height: 20px; width: 50%;"></div>
                <div class="skeleton-shimmer mb-4" style="height: 40px;"></div> {{-- Dropdown --}}
                
                <div class="skeleton-shimmer mb-2" style="height: 20px; width: 50%;"></div>
                <div class="skeleton-shimmer mb-4" style="height: 40px;"></div> {{-- Dropdown --}}
                
                <div class="skeleton-shimmer mt-auto" style="height: 40px; border-radius: 10px;"></div> {{-- Reset Button --}}
            </div>
        </div>

        {{-- 2. Content Area Skeleton --}}
        <div class="col-lg-9">
            {{-- Header Skeleton --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="skeleton-shimmer" style="height: 35px; width: 200px;"></div>
                <div class="skeleton-shimmer" style="height: 35px; width: 150px; border-radius: 50px;"></div>
            </div>

            <div class="row">
                @for($i = 0; $i < 6; $i++)
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card skeleton-card shadow-sm h-100">
                            {{-- Part Image Placeholder --}}
                            <div class="skeleton-shimmer w-100" style="height: 200px; border-radius: 15px 15px 0 0;"></div>
                            
                            <div class="card-body p-3">
                                {{-- Brand/Category Badge --}}
                                <div class="skeleton-shimmer mb-2" style="height: 15px; width: 30%;"></div>
                                
                                {{-- Title --}}
                                <div class="skeleton-shimmer mb-2" style="height: 20px; width: 90%;"></div>
                                
                                {{-- Price and Button --}}
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="skeleton-shimmer" style="height: 25px; width: 40%;"></div>
                                    <div class="skeleton-shimmer" style="height: 35px; width: 35px; border-radius: 50%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>