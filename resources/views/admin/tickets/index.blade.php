@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="font-weight-bold">Support Tickets</h3>
            <p class="text-muted small mb-0">Managing support for Customers & Shop Vendors.</p>
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group shadow-sm rounded-pill bg-white p-1">
                @foreach(['open', 'pending', 'closed', 'all'] as $st)
                <a href="{{ route('admin.tickets.index', ['status' => $st]) }}" 
                   class="btn btn-sm rounded-pill px-3 {{ $status == $st ? 'btn-primary' : 'btn-light' }}">
                   {{ ucfirst($st) }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-xl mb-4">
            <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-xl">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 small text-uppercase font-weight-bold">ID</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold">Source / Type</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold">User/Sender</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold">Subject</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold text-center">Priority</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold text-center">Status</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold">Last Update</th>
                            <th class="border-0 px-4 py-3 small text-uppercase font-weight-bold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="px-4 align-middle">
                                    <span class="font-weight-bold text-dark">#{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="align-middle">
                                    @if($ticket->user->shop)
                                        <span class="badge badge-info-soft text-info rounded-pill px-2">
                                            <i class="fa fa-store mr-1"></i> Shop
                                        </span>
                                        <div class="small font-weight-bold mt-1 text-truncate" style="max-width: 120px;">
                                            {{ $ticket->user->shop->name }}
                                        </div>
                                    @else
                                        <span class="badge badge-secondary-soft text-secondary rounded-pill px-2">
                                            <i class="fa fa-user mr-1"></i> Customer
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px; font-size: 11px; font-weight: bold;">
                                            {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                                        </div>
                                        <div style="line-height: 1.2;">
                                            <div class="font-weight-bold small">{{ $ticket->user->name }}</div>
                                            <div class="text-muted" style="font-size: 10px;">{{ $ticket->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="small font-weight-bold text-truncate" style="max-width: 180px;">{{ $ticket->subject }}</div>
                                    @if($ticket->order_id)
                                        <div class="badge badge-light border small text-muted font-weight-normal">
                                            Order #{{ $ticket->order->order_number ?? $ticket->order_id }}
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    @php
                                        $pClass = ['high' => 'bg-danger', 'medium' => 'bg-warning', 'low' => 'bg-info'][$ticket->priority] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $pClass }} text-white px-2 py-1 rounded-pill small" style="font-size: 9px;">
                                        {{ strtoupper($ticket->priority) }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <span @class([
                                        'small font-weight-bold',
                                        'text-success' => $ticket->status == 'open',
                                        'text-warning' => $ticket->status == 'pending',
                                        'text-muted' => $ticket->status == 'closed'
                                    ])>
                                        <i class="fa fa-circle mr-1" style="font-size: 8px;"></i> {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td class="align-middle small">
                                    <span class="text-dark">{{ $ticket->updated_at->diffForHumans() }}</span><br>
                                    <span class="text-muted" style="font-size: 10px;">{{ $ticket->updated_at->format('M d, H:i') }}</span>
                                </td>
                                <td class="px-4 align-middle text-right">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <p class="text-muted">No tickets found in this category.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tickets->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $tickets->appends(['status' => $status])->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.1); }
    .badge-info-soft { background-color: rgba(23, 162, 184, 0.1); }
    .badge-secondary-soft { background-color: rgba(108, 117, 125, 0.1); }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .rounded-xl { border-radius: 0.75rem !important; }
    .table td { border-top: 1px solid #f2f2f2; }
</style>
@endsection