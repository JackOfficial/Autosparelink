<div>
    <div class="container py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold">Support Tickets</h2>
                <p class="text-muted">Need help with an order or a part request? Our team is here for you.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createTicketModal">
                    <i class="fas fa-plus-circle me-2"></i> Open New Ticket
                </button>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fa-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3 small text-uppercase fw-bold">ID</th>
                                <th class="border-0 py-3 small text-uppercase fw-bold">Subject</th>
                                <th class="border-0 py-3 small text-uppercase fw-bold">Category</th>
                                <th class="border-0 py-3 small text-uppercase fw-bold text-center">Status</th>
                                <th class="border-0 py-3 small text-uppercase fw-bold">Updated</th>
                                <th class="border-0 px-4 py-3 small text-uppercase fw-bold text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr wire:key="ticket-{{ $ticket->id }}">
                                    <td class="px-4 fw-bold text-primary">
                                        #{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $ticket->subject }}</div>
                                        @if($ticket->order_ref)
                                            <span class="badge bg-body-tertiary text-muted fw-normal border">
                                                <i class="fas fa-box-open me-1"></i> {{ $ticket->order_ref }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-capitalize small text-muted">
                                            <i class="fas fa-tag me-1 opacity-50"></i> {{ str_replace('_', ' ', $ticket->category) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusBadge = [
                                                'open'    => 'bg-success',
                                                'pending' => 'bg-warning text-dark',
                                                'closed'  => 'bg-secondary'
                                            ][$ticket->status] ?? 'bg-dark';
                                        @endphp
                                        <span class="badge {{ $statusBadge }} rounded-pill px-3 py-2 small shadow-xs">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $ticket->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="px-4 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <a href="{{ route('user.tickets.show', $ticket->id) }}" class="btn btn-sm btn-white border-end px-3">
                                                View
                                            </a>
                                            @if($ticket->status !== 'closed')
                                                <button 
                                                    wire:click="closeTicket({{ $ticket->id }})" 
                                                    wire:confirm="Close this ticket? This cannot be undone."
                                                    class="btn btn-sm btn-white text-danger px-2"
                                                >
                                                    <i class="fas fa-times" wire:loading.remove wire:target="closeTicket({{ $ticket->id }})"></i>
                                                    <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="closeTicket({{ $ticket->id }})"></div>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/6598/6598519.png" width="80" class="opacity-25 mb-3" alt="No tickets">
                                        <p class="text-muted fw-medium">No active support tickets found.</p>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createTicketModal">
                                            Start a Conversation
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

    <div wire:ignore.self class="modal fade" id="createTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold mt-2 ms-2">Open New Ticket</h5>
                    <button type="button" class="btn-close me-2 mt-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="saveTicket">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">CATEGORY</label>
                            <select wire:model="category" class="form-select border-0 bg-light rounded-3 @error('category') is-invalid @enderror">
                                <option value="">Select Category</option>
                                <option value="order">Order Issues</option>
                                <option value="payment">Payment/Billing</option>
                                <option value="part_request">Part Availability Request</option>
                                <option value="technical">Technical Support</option>
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">ORDER REFERENCE (OPTIONAL)</label>
                            <input type="text" wire:model="order_ref" class="form-control border-0 bg-light rounded-3" placeholder="e.g. #ORD-12345">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">SUBJECT</label>
                            <input type="text" wire:model="subject" class="form-control border-0 bg-light rounded-3 @error('subject') is-invalid @enderror" placeholder="Brief summary">
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-bold text-muted">MESSAGE</label>
                            <textarea wire:model="message" rows="4" class="form-control border-0 bg-light rounded-3 @error('message') is-invalid @enderror" placeholder="How can we help?"></textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-link text-muted text-decoration-none px-4" data-bs-dismiss="modal">Discard</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                            <span wire:loading.remove wire:target="saveTicket">Send Ticket</span>
                            <span wire:loading wire:target="saveTicket">
                                <span class="spinner-border spinner-border-sm me-1"></span> Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('ticket-saved', () => {
        // BS5 JS Method
        const modalElement = document.getElementById('createTicketModal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
    });
</script>
@endscript