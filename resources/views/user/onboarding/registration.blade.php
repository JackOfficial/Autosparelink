@extends('layouts.app')

@section('content')
<div class="container py-5" x-data="{ step: 1 }">
    <div class="row align-items-center">
        <div class="col-lg-5 mb-5 mb-lg-0">
            <div class="mb-4">
                <span class="badge badge-pill badge-primary px-3 py-2 fw-bold text-uppercase mb-3" 
                      style="font-size: 0.7rem; letter-spacing: 1px; background-color: rgba(0, 123, 255, 0.1); color: #007bff;">
                    Vendor Partnership
                </span>
                <h1 class="display-5 font-weight-bold text-dark">Grow your spare parts business.</h1>
                <p class="lead text-muted">Complete these 3 quick steps to start selling to customers across Rwanda.</p>
            </div>

            <div class="stepper-nav mt-4">
                <div class="d-flex align-items-center mb-3" :class="step >= 1 ? 'text-primary' : 'text-muted'">
                    <div class="rounded-circle border d-flex align-items-center justify-content-center mr-3" 
                         :class="step >= 1 ? 'bg-primary text-white border-primary' : 'bg-white'"
                         style="width: 35px; height: 35px; font-weight: bold;">1</div>
                    <span class="font-weight-bold">Shop Information</span>
                </div>
                <div class="d-flex align-items-center mb-3" :class="step >= 2 ? 'text-primary' : 'text-muted'">
                    <div class="rounded-circle border d-flex align-items-center justify-content-center mr-3" 
                         :class="step >= 2 ? 'bg-primary text-white border-primary' : 'bg-white'"
                         style="width: 35px; height: 35px; font-weight: bold;">2</div>
                    <span class="font-weight-bold">Contact & Location</span>
                </div>
                <div class="d-flex align-items-center" :class="step >= 3 ? 'text-primary' : 'text-muted'">
                    <div class="rounded-circle border d-flex align-items-center justify-content-center mr-3" 
                         :class="step >= 3 ? 'bg-primary text-white border-primary' : 'bg-white'"
                         style="width: 35px; height: 35px; font-weight: bold;">3</div>
                    <span class="font-weight-bold">Verification</span>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-lg rounded-5 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('shop.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div x-show="step === 1" x-transition>
                            <h4 class="font-weight-bold mb-4">Tell us about your shop</h4>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small">Shop Name</label>
                                <input type="text" name="shop_name" class="form-control bg-light rounded-4 py-4" placeholder="e.g. Kigali Tech Auto" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark small">Specialization</label>
                                <textarea name="description" class="form-control bg-light rounded-4" rows="3" placeholder="What parts do you sell?"></textarea>
                            </div>
                            <button type="button" @click="step = 2" class="btn btn-primary btn-block rounded-pill py-3 font-weight-bold">
                                Next: Contact Details <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>

                        <div x-show="step === 2" x-transition style="display: none;">
                            <h4 class="font-weight-bold mb-4">Contact Information</h4>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="small font-weight-bold">Business Email</label>
                                    <input type="email" name="shop_email" class="form-control bg-light py-4" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="small font-weight-bold">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control bg-light py-4" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">TIN Number</label>
                                <input type="text" name="tin_number" class="form-control bg-light py-4" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="small font-weight-bold">Physical Address</label>
                                <input type="text" name="address" class="form-control bg-light py-4" placeholder="District, Sector..." required>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" @click="step = 1" class="btn btn-outline-secondary btn-block rounded-pill py-3">Back</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" @click="step = 3" class="btn btn-primary btn-block rounded-pill py-3 font-weight-bold">Next Step</button>
                                </div>
                            </div>
                        </div>

                        <div x-show="step === 3" x-transition style="display: none;">
                            <h4 class="font-weight-bold mb-4">Verification Documents</h4>
                            <div class="p-4 rounded-4 border bg-light mb-4" style="border-style: dashed !important; border-width: 2px !important;">
                                <div class="form-group">
                                    <label class="small font-weight-bold">RDB Certificate (PDF/Image)</label>
                                    <input type="file" name="rdb_certificate" class="form-control-file">
                                </div>
                                <hr>
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold">Owner National ID</label>
                                    <input type="file" name="owner_id" class="form-control-file">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <button type="button" @click="step = 2" class="btn btn-outline-secondary btn-block rounded-pill py-3">Back</button>
                                </div>
                                <div class="col-8">
                                    <button type="submit" class="btn btn-success btn-block rounded-pill py-3 font-weight-bold shadow-lg">
                                        Submit Application <i class="fas fa-check-circle ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-5 { border-radius: 2rem !important; }
    .rounded-4 { border-radius: 1.2rem !important; }
    .bg-light { background-color: #f8f9fa !important; }
    .display-5 { font-size: 2.3rem; line-height: 1.2; }
    [x-cloak] { display: none !important; }
    .form-control:focus { box-shadow: none; border-color: #007bff; background: #fff !important; }
</style>
@endsection