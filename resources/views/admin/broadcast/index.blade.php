@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Header Section --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="font-weight-bold mb-0">Marketing Broadcasts</h3>
                    <p class="text-muted small">Notify all users about promotions or system updates.</p>
                </div>

                {{-- Clear All Button --}}
                @if($history->count() > 0)
                    <form action="{{ route('admin.broadcast.clearAll') }}" method="POST" onsubmit="return confirm('ATTENTION: This will permanently delete ALL broadcast history. This cannot be undone. Continue?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 font-weight-bold transition-3s">
                            <i class="fas fa-eraser mr-1"></i> Clear History
                        </button>
                    </form>
                @endif
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Create Broadcast Form --}}
            <div class="card border-0 shadow-sm mb-5" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <form action="{{ route('admin.broadcast.send') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label class="x-small font-weight-bold text-uppercase text-muted ml-2">Broadcast Message</label>
                                    <textarea name="message" rows="3" class="form-control border-0 bg-light p-3" 
                                              style="border-radius: 15px; resize: none;" 
                                              placeholder="Type your message here..." required></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="x-small font-weight-bold text-uppercase text-muted ml-2">Type</label>
                                    <select name="type" class="form-control custom-pill-input border-0 bg-light">
                                        <option value="update">📢 System Update</option>
                                        <option value="promo">🎁 Promotion</option>
                                        <option value="alert">⚠️ Urgent Alert</option>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="x-small font-weight-bold text-uppercase text-muted ml-2">Action URL (Optional)</label>
                                    <input type="url" name="url" class="form-control custom-pill-input border-0 bg-light" placeholder="https://...">
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 font-weight-bold shadow-sm transition-3s">
                                Send to All Users <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- History Table --}}
            <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 font-weight-bold">Broadcast History</h6>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="x-small font-weight-bold text-uppercase text-muted border-0">Date</th>
                                <th class="x-small font-weight-bold text-uppercase text-muted border-0">Type</th>
                                <th class="x-small font-weight-bold text-uppercase text-muted border-0">Message</th>
                                <th class="x-small font-weight-bold text-uppercase text-muted border-0 text-center">Recipients</th>
                                <th class="x-small font-weight-bold text-uppercase text-muted border-0 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $broadcast)
                            <tr>
                                <td class="small text-muted">{{ $broadcast->created_at->format('d M, Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-pill py-2 px-3 
                                        {{ $broadcast->type == 'promo' ? 'bg-success-light text-success' : ($broadcast->type == 'alert' ? 'bg-danger-light text-danger' : 'bg-primary-light text-primary') }}">
                                        {{ ucfirst($broadcast->type) }}
                                    </span>
                                </td>
                                <td class="small font-weight-bold text-dark">{{ Str::limit($broadcast->message, 60) }}</td>
                                <td class="text-center font-weight-bold text-primary">{{ $broadcast->recipient_count }}</td>
                                <td class="text-right">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <a href="{{ route('admin.broadcast.show', $broadcast->id) }}" class="btn btn-sm btn-light rounded-pill px-3 font-weight-bold mr-2">
                                            Details
                                        </a>
                                        
                                        {{-- Delete Individual Form --}}
                                        <form action="{{ route('admin.broadcast.destroy', $broadcast->id) }}" method="POST" onsubmit="return confirm('Delete this broadcast from history?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center transition-3s" style="width: 32px; height: 32px; padding: 0;">
                                                <i class="fas fa-trash-alt" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No broadcasts sent yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-pill-input { height: 45px !important; border-radius: 50px !important; padding: 0 1rem !important; font-size: 0.85rem !important; }
    .x-small { font-size: 0.7rem; letter-spacing: 0.5px; }
    .bg-primary-light { background: rgba(59, 130, 246, 0.1); }
    .bg-success-light { background: rgba(16, 185, 129, 0.1); }
    .bg-danger-light { background: rgba(239, 68, 68, 0.1); }
    .transition-3s { transition: all 0.3s ease; }
    .transition-3s:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; }
    .btn-outline-danger:hover { background-color: #ef4444; color: white; border-color: #ef4444; }
</style>
@endsection