@extends('admin.layouts.app')
@section('title', 'Review Moderation')

@section('content')
<div class="container-fluid mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 font-weight-bold mb-1">Review Moderation Queue</h2>
            <p class="text-muted small mb-0">Approve or reject customer reviews for parts and shops.</p>
        </div>
        <div class="badge bg-warning text-dark px-3 py-2 fs-6 shadow-sm">
            {{ $reviews->total() }} Pending Reviews
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted text-uppercase small font-weight-bold border-bottom">
                    <tr>
                        <th class="px-4 py-3" style="width: 15%;">Customer</th>
                        <th style="width: 20%;">Item / Target</th>
                        <th style="width: 15%;">Rating</th>
                        <th style="width: 35%;">Comment</th>
                        <th class="text-end px-4" style="width: 15%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr class="border-bottom">
                            <td class="px-4">
                                <div class="font-weight-bold text-dark">{{ $review->user->name ?? 'Unknown User' }}</div>
                                <small class="text-muted">{{ $review->created_at->format('d M Y, H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary-soft text-secondary text-capitalize mb-1 px-2 py-1">
                                    {{ $review->reviewable_type }}
                                </span>
                                <div class="font-weight-semibold text-dark">{{ $review->reviewable->name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="text-warning font-weight-bold">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            ★
                                        @else
                                            <span class="text-muted-light">☆</span>
                                        @endif
                                    @endfor
                                    <span class="text-dark small ms-1">({{ $review->rating }}/5)</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-wrap text-secondary pr-3" style="max-width: 400px; word-break: break-word;">
                                    {{ $review->comment ?: '— No text comment left —' }}
                                </div>
                            </td>
                            <td class="text-end px-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm px-3 shadow-sm rounded-pill font-weight-medium">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill font-weight-medium" 
                                                onclick="return confirm('Are you sure you want to reject and drop this review?')">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-chat-left-check fs-2 text-muted-light d-block mb-2"></i>
                                Good job! The review moderation queue is clean and empty.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center border-top">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    /* Styling refinements matching UI clean aesthetics */
    .bg-secondary-soft { background-color: rgba(108, 117, 125, 0.12); }
    .table thead th { border-top: 0; background-color: #f8f9fa; color: #6c757d !important; letter-spacing: 0.5px; }
    .table tbody tr:hover { background-color: #fafbfc; }
    .text-muted-light { color: #dee2e6; }
    .font-weight-semibold { font-weight: 500; }
    .gap-2 { gap: 0.5rem; }
</style>
@endsection