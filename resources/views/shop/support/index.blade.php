<x-shop-dashboard>
    <x-slot:title>Support Tickets</x-slot:title>

    <div class="container-fluid py-4" x-data="{ filter: 'all' }">
        {{-- Header Section --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Support Center</h4>
                <p class="text-muted small mb-0">Manage your inquiries and technical support requests.</p>
            </div>
            <a href="{{ route('shop.support.create') }}" class="btn btn-primary px-4 fw-bold shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Open New Ticket
            </a>
        </div>

        {{-- Quick Stats / Filter Tabs --}}
        <div class="row g-3 mb-4">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-2">
                        <ul class="nav nav-pills nav-fill gap-2">
                            <li class="nav-item">
                                <button @click="filter = 'all'" :class="filter === 'all' ? 'btn-primary' : 'btn-light'" class="btn w-100 fw-bold border-0">
                                    All Tickets <span class="badge bg-white text-primary ms-2">{{ $tickets->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button @click="filter = 'pending'" :class="filter === 'pending' ? 'btn-primary' : 'btn-light'" class="btn w-100 fw-bold border-0">
                                    Pending <span class="badge bg-white text-primary ms-2">{{ $tickets->where('status', 'pending')->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button @click="filter = 'answered'" :class="filter === 'answered' ? 'btn-primary' : 'btn-light'" class="btn w-100 fw-bold border-0">
                                    Answered <span class="badge bg-white text-primary ms-2">{{ $tickets->where('status', 'answered')->count() }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tickets Table --}}
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase fw-bold border-0" style="width: 40%;">Ticket Details</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0 text-center">Priority</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0 text-center">Category</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0 text-center">Status</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0 text-end pe-4">Last Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr x-show="filter === 'all' || filter === '{{ $ticket->status }}'" x-transition>
                            <td class="ps-4 py-3">
                                <a href="{{ route('shop.support.show', $ticket->id) }}" class="text-decoration-none text-dark d-block">
                                    <span class="fw-bold d-block mb-1">{{ $ticket->subject }}</span>
                                    <span class="text-muted small">#ST-{{ $ticket->id }} • @if($ticket->order_id) Order #{{ $ticket->order->order_number }} @else General @endif</span>
                                </a>
                            </td>
                            <td class="text-center">
                                @php
                                    $priorityColors = ['high' => 'bg-danger', 'medium' => 'bg-warning text-dark', 'low' => 'bg-info text-white'];
                                @endphp
                                <span class="badge {{ $priorityColors[$ticket->priority] ?? 'bg-secondary' }} rounded-pill small px-3">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="text-center text-muted small">
                                {{ $ticket->category }}
                            </td>
                            <td class="text-center">
                                <span @class([
                                    'badge py-2 px-3 rounded-pill',
                                    'bg-success-subtle text-success' => $ticket->status == 'answered',
                                    'bg-warning-subtle text-warning' => $ticket->status == 'pending',
                                    'bg-secondary-subtle text-secondary' => $ticket->status == 'closed',
                                ])>
                                    <i class="fas fa-circle me-1 small"></i> {{ strtoupper($ticket->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <span class="text-muted small d-block">{{ $ticket->updated_at->diffForHumans() }}</span>
                                <span class="text-muted small" style="font-size: 0.7rem;">{{ $ticket->updated_at->format('d M, Y') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-ticket-alt fa-3x text-light mb-3"></i>
                                    <p class="text-muted fw-bold">No support tickets found.</p>
                                    <a href="{{ route('shop.support.create') }}" class="btn btn-sm btn-outline-primary mt-2">Create your first ticket</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tickets->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-shop-dashboard>