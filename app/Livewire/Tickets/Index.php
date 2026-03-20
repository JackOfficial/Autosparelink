<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    // Form properties linked via wire:model
    public $category = '';
    public $subject = '';
    public $message = '';
    public $order_ref = '';

    // Ensures pagination links use Bootstrap styles
    protected $paginationTheme = 'bootstrap';

    /**
     * Validation rules matching your controller logic
     */
    protected $rules = [
        'category'  => 'required|string|in:order,payment,part_request,technical',
        'subject'   => 'required|string|min:5|max:255',
        'message'   => 'required|string|min:10',
        'order_ref' => 'nullable|string|max:100',
    ];

    /**
     * Save a new support ticket using Livewire
     */
    public function saveTicket()
    {
        // 1. Run validation
        $validated = $this->validate();

        // 2. Create ticket using the authenticated user relationship
        $ticket = Auth::user()->tickets()->create([
            'category'  => $validated['category'],
            'subject'   => $validated['subject'],
            'message'   => $validated['message'],
            'order_ref' => $validated['order_ref'],
            'status'    => 'open',
            'priority'  => 'medium', // Default priority from your store method
        ]);

        // 3. Reset form fields for the next use
        $this->reset(['category', 'subject', 'message', 'order_ref']);

        // 4. Trigger the JavaScript to hide the Bootstrap modal
        $this->dispatch('ticket-saved');

        // 5. Flash success message exactly as defined in your controller
        session()->flash('success', "Ticket #{$ticket->id} has been opened. Our team will review it shortly.");
    }

    public function render()
    {
        // Fetch paginated tickets for the UI
        $tickets = Auth::user()->tickets()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.tickets.index', [
            'tickets' => $tickets
        ]);
    }
}