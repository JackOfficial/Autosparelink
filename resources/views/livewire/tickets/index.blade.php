<div>
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

        @if (session()->has('success'))
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
                                <tr wire:key="ticket-{{ $ticket->id }}">
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
                                        <div class="btn-group">
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 mr-2">
                                                View
                                            </a>
                                            
                                            {{-- Suggested Quick Close Action --}}
                                            @if($ticket->status !== 'closed')
                                                <button 
                                                    wire:click="closeTicket({{ $ticket->id }})" 
                                                    wire:confirm="Are you sure you want to close this ticket?"
                                                    class="btn btn-sm btn-outline-secondary rounded-pill"
                                                    title="Mark as Closed"
                                                >
                                                    <i class="fa fa-times-circle" wire:loading.remove wire:target="closeTicket({{ $ticket->id }})"></i>
                                                    <i class="fa fa-spinner fa-spin" wire:loading wire:target="closeTicket({{ $ticket->id }})"></i>
                                                </button>
                                            @endif
                                        </div>
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

    {{-- Modal remains the same but ensure wire:model.blur for performance --}}
    <div wire:ignore.self class="modal fade" id="createTicketModal" tabindex="-1" role="dialog" aria-labelledby="createTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg rounded-xl">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title font-weight-bold" id="createTicketModalLabel">Open New Support Ticket</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="saveTicket">
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">CATEGORY</label>
                            <select wire:model.blur="category" class="form-control rounded-pill border-0 bg-light @error('category') is-invalid @enderror">
                                <option value="">Select Category</option>
                                <option value="order">Order Issues</option>
                                <option value="payment">Payment/Billing</option>
                                <option value="part_request">Part Availability Request</option>
                                <option value="technical">Technical Support</option>
                            </select>
                            @error('category') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">ORDER REFERENCE (OPTIONAL)</label>
                            <input type="text" wire:model.blur="order_ref" class="form-control rounded-pill border-0 bg-light" placeholder="e.g. #ORD-12345">
                            @error('order_ref') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">SUBJECT</label>
                            <input type="text" wire:model.blur="subject" class="form-control rounded-pill border-0 bg-light @error('subject') is-invalid @enderror" placeholder="Brief summary of the issue">
                            @error('subject') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted">MESSAGE</label>
                            <textarea wire:model.blur="message" rows="4" class="form-control border-0 bg-light @error('message') is-invalid @enderror" style="border-radius: 15px;" placeholder="Describe your problem in detail..."></textarea>
                            @error('message') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <span wire:loading.remove wire:target="saveTicket">Submit Ticket</span>
                            <span wire:loading wire:target="saveTicket">
                                <i class="fa fa-spinner fa-spin mr-1"></i> Saving...
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
        $('#createTicketModal').modal('hide');
        // Clean up Bootstrap modal artifacts
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('padding-right', '');
    });
</script>
@endscript