@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="font-weight-bold">Support Tickets</h2>
            <p class="text-muted">Need help with an order or a part request? Our team is here for you.</p>
        </div>
        <div class="col-md-6 text-md-right">
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-toggle="modal" data-target="#createTicketModal">
                <i class="fa fa-plus-circle mr-2"></i> Open New Ticket
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-lg mb-4">
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
                            <th class="border-0 py-3 small text-uppercase font-weight-bold">Subject</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold">Category</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold text-center">Status</th>
                            <th class="border-0 py-3 small text-uppercase font-weight-bold">Last Updated</th>
                            <th class="border-0 px-4 py-3 small text-uppercase font-weight-bold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="px-4 align-middle font-weight-bold text-primary">
                                    #{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="align-middle">
                                    <div class="font-weight-bold text-dark">{{ $ticket->subject }}</div>
                                    @if($ticket->order_ref)
                                        <small class="text-muted">Ref: {{ $ticket->order_ref }}</small>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-light border text-capitalize px-2 py-1">
                                        {{ str_replace('_', ' ', $ticket->category) }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    @php
                                        $statusClass = [
                                            'open'    => 'badge-success',
                                            'pending' => 'badge-warning',
                                            'closed'  => 'badge-secondary'
                                        ][$ticket->status] ?? 'badge-dark';
                                    @endphp
                                    <span class="badge {{ $statusClass }} rounded-pill px-3 py-2 small shadow-sm">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td class="align-middle small">
                                    {{ $ticket->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-4 align-middle text-right">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="opacity-50 mb-3">
                                        <i class="fa fa-ticket-alt fa-3x"></i>
                                    </div>
                                    <p class="text-muted">You haven't opened any support tickets yet.</p>
                                    <button class="btn btn-sm btn-primary rounded-pill px-4" data-toggle="modal" data-target="#createTicketModal">
                                        Create your first ticket
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tickets->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="createTicketModal" tabindex="-1" role="dialog" aria-labelledby="createTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-xl">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title font-weight-bold" id="createTicketModalLabel">Open New Support Ticket</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">CATEGORY</label>
                        <select name="category" class="form-control rounded-pill border-0 bg-light" required>
                            <option value="order">Order Issues</option>
                            <option value="payment">Payment/Billing</option>
                            <option value="part_request">Part Availability Request</option>
                            <option value="technical">Technical Support</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">ORDER REFERENCE (OPTIONAL)</label>
                        <input type="text" name="order_ref" class="form-control rounded-pill border-0 bg-light" placeholder="e.g. #ORD-12345">
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">SUBJECT</label>
                        <input type="text" name="subject" class="form-control rounded-pill border-0 bg-light" placeholder="Brief summary of the issue" required>
                    </div>

                    <div class="form-group mb-0">
                        <label class="small font-weight-bold text-muted">MESSAGE</label>
                        <textarea name="message" rows="4" class="form-control border-0 bg-light" style="border-radius: 15px;" placeholder="Describe your problem in detail..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .rounded-xl { border-radius: 1rem !important; }
    .table-hover tbody tr:hover { background-color: #fcfcfc; }
    .badge-success { background-color: #28a745; }
    .badge-warning { background-color: #ffc107; color: #333; }
    .badge-secondary { background-color: #6c757d; }
    .btn-primary { background-color: #007bff; border: none; }
</style>
@endsection