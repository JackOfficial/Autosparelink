@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="mb-4">
        <h3 class="font-weight-bold">System Settings</h3>
        <p class="text-muted small">Configure the global settings for {{ setting('site_name', 'autosparepart.com') }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-xl">
            <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase text-muted">Site Name</label>
                                <input type="text" name="site_name" class="form-control rounded-pill bg-light border-0" 
                                       value="{{ setting('site_name', 'Auto Spare Part') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase text-muted">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control rounded-pill bg-light border-0" 
                                       value="{{ setting('contact_email', 'admin@autosparepart.com') }}">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-uppercase text-muted">Site Logo</label>
                            <div class="custom-file">
                                <input type="file" name="site_logo" class="custom-file-input" id="siteLogo">
                                <label class="custom-file-label rounded-pill bg-light border-0" for="siteLogo">
                                    {{ setting('site_logo') ? 'Change current logo' : 'Choose file' }}
                                </label>
                            </div>
                            
                            @if(setting('site_logo'))
                                <div class="mt-3 p-2 bg-light rounded d-inline-block border">
                                    <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="Current Logo" height="45">
                                    <small class="d-block text-muted text-center mt-1">Current Preview</small>
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase text-muted">Default Currency</label>
                                <select name="currency" class="form-control rounded-pill bg-light border-0">
                                    <option value="USD" {{ setting('currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                    <option value="RWF" {{ setting('currency') == 'RWF' ? 'selected' : '' }}>RWF (RF)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-uppercase text-muted">Maintenance Mode</label>
                                <select name="maintenance_mode" class="form-control rounded-pill bg-light border-0">
                                    <option value="0" {{ setting('maintenance_mode') == '0' ? 'selected' : '' }}>Disabled (Live)</option>
                                    <option value="1" {{ setting('maintenance_mode') == '1' ? 'selected' : '' }}>Enabled (Offline)</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                <i class="fa fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-xl bg-dark text-white mb-3">
                <div class="card-body">
                    <h5 class="font-weight-bold text-primary"><i class="fa fa-shield-alt mr-2"></i> Security Info</h5>
                    <p class="small opacity-75">Ensure your contact email is active. This address is used for critical system alerts and customer inquiry copies.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-xl border-left-info">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-2 small text-uppercase text-muted">Usage Tip</h6>
                    <p class="small text-muted mb-0">You can use <code>setting('site_name')</code> anywhere in your frontend code to sync this value across the website.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection