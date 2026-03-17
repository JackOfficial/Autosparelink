@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="mb-4">
        <h3 class="font-weight-bold">System Settings</h3>
        <p class="text-muted">Configure the global settings for autosparepart.com</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-xl">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small">Site Name</label>
                                <input type="text" name="site_name" class="form-control rounded-pill bg-light border-0" 
                                       value="{{ $settings['site_name'] ?? 'Auto Spare Part' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control rounded-pill bg-light border-0" 
                                       value="{{ $settings['contact_email'] ?? 'admin@autosparepart.com' }}">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small">Site Logo</label>
                            <div class="custom-file">
                                <input type="file" name="site_logo" class="custom-file-input" id="siteLogo">
                                <label class="custom-file-label rounded-pill bg-light border-0" for="siteLogo">Choose file</label>
                            </div>
                            @if(isset($settings['site_logo']))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" height="40">
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small">Default Currency</label>
                                <select name="currency" class="form-control rounded-pill bg-light border-0">
                                    <option value="USD" {{ ($settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                    <option value="RWF" {{ ($settings['currency'] ?? '') == 'RWF' ? 'selected' : '' }}>RWF (RF)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small">Maintenance Mode</label>
                                <select name="maintenance_mode" class="form-control rounded-pill bg-light border-0">
                                    <option value="0" {{ ($settings['maintenance_mode'] ?? '') == '0' ? 'selected' : '' }}>Disabled</option>
                                    <option value="1" {{ ($settings['maintenance_mode'] ?? '') == '1' ? 'selected' : '' }}>Enabled</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-xl bg-dark text-white p-2">
                <div class="card-body">
                    <h5 class="font-weight-bold"><i class="fa fa-info-circle mr-2"></i> Note</h5>
                    <p class="small opacity-75">Changing these settings will affect all users on the storefront immediately. Please double-check your contact email for order notifications.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection