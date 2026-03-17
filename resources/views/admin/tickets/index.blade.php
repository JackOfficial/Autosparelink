@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="font-weight-bold">Support Tickets</h3>
            <p class="text-muted small mb-0">Manage customer inquiries and technical support requests.</p>
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group shadow-sm rounded-pill bg-white p-1">
                <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" 
                   class="btn btn-sm rounded-pill px-3 {{ $status == 'open' ? 'btn-primary' : 'btn-light' }}">Open</a>
                <a href="{{ route('admin.tickets.index', ['status' => 'pending']) }}" 
                   class="btn btn-sm rounded-pill px-3 {{ $status == 'pending' ? 'btn-primary' : 'btn-light' }}">Pending</a>
                <a href="{{ route('admin.tickets.index', ['status' => 'closed']) }}" 
                   class="btn btn-sm rounded-pill px-3 {{ $status == 'closed' ? 'btn-primary' : 'btn-light' }}">Closed</a>
                <a href="{{ route('admin.tickets.index', ['status' => 'all']) }}" 
                   class="btn btn-sm rounded-pill px-3 {{ $status == 'all' ? 'btn-primary' : 'btn-light' }}">All</a>
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
                            <th class="border-0 px-4 py-3 small text-uppercase font-weight-bold">Ticket ID</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold">Customer</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold">Subject</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold text-center">Priority</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold text-center">Status</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold">Created</th>
                            <th class="border-0 px-4 py-3 small text-uppercase font-weight-bold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="px-4 align-middle">
                                    <span class="font-weight-bold text-primary">#TK-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px; font-size: 12px;">
                                            {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold small">{{ $ticket->user->name }}</div>
                                            <div class="text-muted" style="font-size: 11px;">{{ $ticket->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="small text-truncate" style="max-width: 200px;">{{ $ticket->subject }}</div>
                                </td>
                                <td class="align-middle text-center">
                                    @php
                                        $priorityClass = [
                                            'high' => 'badge-danger',
                                            'medium' => 'badge-warning',
                                            'low' => 'badge-info'
                                        ][$ticket->priority] ?? 'badge-secondary';
                                    @endphp
                                    <span class="badge {{ $priorityClass }} px-3 py-2 rounded-pill small text-uppercase" style="font-size: 10px;">
                                        {{ $ticket->priority }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    @php
                                        $statusClass = [
                                            'open' => 'text-success',
                                            'pending' => 'text-warning',
                                            'closed' => 'text-muted'
                                        ][$ticket->status] ?? 'text-dark';
                                    @endphp
                                    <span class="small font-weight-bold {{ $statusClass }}">
                                        <i class="fa fa-circle mr-1" style="font-size: 8px;"></i> {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td class="align-middle small">
                                    {{ $ticket->created_at->format('M d, Y') }}<br>
                                    <span class="text-muted">{{ $ticket->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-4 align-middle text-right">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-light border rounded-pill px-3 shadow-sm">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <img src="{{ asset('frontend/img/no-data.png') }}" alt="No Tickets" style="width: 80px;" class="mb-3 opacity-50">
                                    <p class="text-muted">No {{ $status }} tickets found at the moment.</p>
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
    .table-hover tbody tr:hover { background-color: #fbfbfb; cursor: pointer; }
    .rounded-xl { border-radius: 1rem !important; }
</style>
@endsection