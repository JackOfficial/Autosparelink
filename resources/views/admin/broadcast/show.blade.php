@extends('admin.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <a href="{{ route('admin.broadcast.index') }}" class="text-muted small font-weight-bold text-decoration-none mb-3 d-inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to History
            </a>
            
            <div class="card border-0 shadow-lg" style="border-radius: 25px; overflow: hidden;">
                <div class="card-header p-4 text-center border-0" style="background: linear-gradient(45deg, #0f172a, #1e293b);">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 60px; height: 60px;">
                        <i class="fas fa-bullhorn text-white fa-lg"></i>
                    </div>
                    <h5 class="text-white font-weight-bold mb-0">Broadcast Details</h5>
                    <p class="small text-white-50 mb-0">Sent on {{ $broadcast->created_at->format('F d, Y @ H:i') }}</p>
                </div>

                <div class="card-body p-4 bg-light">
                    <div class="mb-4">
                        <label class="x-small font-weight-bold text-uppercase text-muted d-block mb-1">Message</label>
                        <div class="p-3 bg-white shadow-sm font-weight-bold text-dark" style="border-radius: 15px; line-height: 1.6;">
                            {{ $broadcast->message }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label class="x-small font-weight-bold text-uppercase text-muted d-block mb-1">Category</label>
                            <p class="font-weight-bold text-primary">{{ ucfirst($broadcast->type) }}</p>
                        </div>
                        <div class="col-6">
                            <label class="x-small font-weight-bold text-uppercase text-muted d-block mb-1">Recipients</label>
                            <p class="font-weight-bold text-dark">{{ $broadcast->recipient_count }} users notified</p>
                        </div>
                    </div>

                    @if($broadcast->url)
                    <div class="mt-2">
                        <label class="x-small font-weight-bold text-uppercase text-muted d-block mb-1">Target Link</label>
                        <a href="{{ $broadcast->url }}" target="_blank" class="text-truncate d-block small font-weight-bold">{{ $broadcast->url }}</a>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-white border-0 p-4 text-center">
                    <button class="btn btn-outline-dark rounded-pill px-4 font-weight-bold small" onclick="window.print()">
                        <i class="fas fa-print mr-2"></i> Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection