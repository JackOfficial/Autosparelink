@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row">
        <div class="col-lg-3">
            <a href="#" class="btn btn-primary btn-block mb-3 py-2 rounded-pill shadow-sm">
                <i class="fa fa-sync-alt mr-2"></i> Refresh Inbox
            </a>
            
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush rounded-xl">
                        <a href="{{ route('admin.mailbox.index', ['status' => 'active']) }}" 
                           class="list-group-item list-group-item-action border-0 {{ $status == 'active' ? 'bg-primary text-white' : '' }}">
                            <i class="fa fa-inbox mr-2"></i> Inbox
                            <span class="badge badge-pill float-right {{ $status == 'active' ? 'badge-light' : 'badge-primary' }}">
                                {{ \App\Models\Contact::where('status', 'active')->count() }}
                            </span>
                        </a>
                        <a href="{{ route('admin.mailbox.index', ['status' => 'resolved']) }}" 
                           class="list-group-item list-group-item-action border-0 {{ $status == 'resolved' ? 'bg-success text-white' : '' }}">
                            <i class="fa fa-check-circle mr-2"></i> Resolved
                        </a>
                        <a href="{{ route('admin.mailbox.index', ['status' => 'archived']) }}" 
                           class="list-group-item list-group-item-action border-0 {{ $status == 'archived' ? 'bg-dark text-white' : '' }}">
                            <i class="fa fa-archive mr-2"></i> Archived
                        </a>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        {{ ucfirst($status) }} Messages
                    </h5>
                    <form action="{{ route('admin.mailbox.index') }}" method="GET" class="form-inline">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="text" name="search" class="form-control form-control-sm rounded-pill border-light bg-light px-3" 
                               placeholder="Search messages..." value="{{ request('search') }}">
                    </form>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @forelse($messages as $msg)
                                    <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.mailbox.read', $msg->id) }}'">
                                        <td class="pl-4" style="width: 50px;">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="check{{ $msg->id }}">
                                                <label class="custom-control-label" for="check{{ $msg->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-dark" style="width: 200px;">
                                            {{ \Illuminate\Support\Str::limit($msg->name ?? $msg->email, 20) }}
                                        </td>
                                        <td>
                                            <span class="text-dark">{{ \Illuminate\Support\Str::limit($msg->message, 60) }}</span>
                                        </td>
                                        <td class="text-right text-muted small pr-4">
                                            {{ $msg->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" width="80" class="mb-3 opacity-50">
                                            <p class="text-muted">No messages found in {{ $status }} folder.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($messages->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $messages->appends(['status' => $status])->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item { transition: all 0.2s ease; font-weight: 500; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .rounded-xl { border-radius: 15px !important; }
</style>
@endsection