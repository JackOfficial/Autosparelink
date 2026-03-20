<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public $ticketId;
    public $message = '';

    /**
     * Accepting $id as a simple variable to avoid Route Model Binding 403s.
     */
    public function mount($id)
    {
        // Check if the ticket exists and belongs to the user
        $ticket = Ticket::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->first();

        if (!$ticket) {
            abort(403, 'You are not authorized to view this ticket.');
        }

        $this->ticketId = $ticket->id;
    }

    protected $rules = [
        'message' => 'required|string|min:2|max:5000',
    ];

    /**
     * Computed Property: Efficiently fetches the ticket with replies.
     */
    public function getTicketProperty()
    {
        return Auth::user()->tickets()
            ->with(['replies.user'])
            ->findOrFail($this->ticketId);
    }

    public function sendReply()
    {
        $this->validate();

        $ticket = $this->ticketProperty; // Uses the computed property above

        if ($ticket->status === 'closed') {
            session()->flash('error', 'This ticket is closed and cannot receive replies.');
            return;
        }

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);

        // Update status so support knows there is a new message
        $ticket->update(['status' => 'open']);

        $this->reset('message');
        $this->dispatch('reply-sent');

        session()->flash('success', 'Your reply has been sent.');
    }

    public function render()
    {
        return view('livewire.tickets.show', [
            'ticket' => $this->ticketProperty
        ]);
    }
}