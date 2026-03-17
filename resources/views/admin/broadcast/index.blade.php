@extends('admin.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-header bg-dark text-white p-4" style="border-radius: 20px 20px 0 0; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <h4 class="mb-0 font-weight-bold"><i class="fas fa-broadcast-tower mr-2 text-primary"></i> Create Broadcast</h4>
                    <p class="small text-white-50 mb-0">Send a real-time notification to every registered user.</p>
                </div>

                <div class="card-body p-5 bg-light">
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 15px;">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.broadcast.send') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-uppercase text-muted">Message Content</label>
                            <textarea name="message" class="form-control border-0 shadow-sm p-3" rows="3" 
                                style="border-radius: 15px;" placeholder="What do you want to tell your users?" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-4">
                                <label class="small font-weight-bold text-uppercase text-muted">Notification Type</label>
                                <select name="type" class="form-control border-0 shadow-sm" style="border-radius: 50px; height: 50px;">
                                    <option value="update">📢 General Update</option>
                                    <option value="promo">🎁 Promotion / Discount</option>
                                    <option value="alert">⚠️ Important Alert</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group mb-4">
                                <label class="small font-weight-bold text-uppercase text-muted">Action URL (Optional)</label>
                                <input type="url" name="url" class="form-control border-0 shadow-sm" 
                                    style="border-radius: 50px; height: 50px;" placeholder="https://autosparelink.com/promo">
                            </div>
                        </div>

                        <div class="text-right mt-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 font-weight-bold shadow transition-3s">
                                Send Broadcast <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-3s { transition: all 0.3s ease; }
    .transition-3s:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2) !important; }
</style>
@endsection