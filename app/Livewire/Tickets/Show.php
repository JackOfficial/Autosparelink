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
     * Captures the ID and performs an initial security check.
     */
    public function mount($id)
    {
        $this->ticketId = $id;
        
        // Ensure the ticket exists and belongs to the user
        $this->getTicket();
    }

    /**
     * Validation rules for replies
     */
    protected $rules = [
        'message' => 'required|string|min:2|max:5000',
    ];

    /**
     * Helper method to fetch the ticket with replies and users.
     * Uses eager loading to keep the SMM panel fast.
     */
    public function getTicket()
    {
        return Auth::user()->tickets()
            ->with(['replies.user'])
            ->findOrFail($this->ticketId);
    }

    /**
     * Handle sending a new reply and triggering the scroll event.
     */
    public function sendReply()
    {
        $this->validate();

        $ticket = $this->getTicket();

        // Security check: Don't allow replies to closed tickets
        if ($ticket->status === 'closed') {
            $this->addError('message', 'This ticket is closed and cannot receive further replies.');
            return;
        }

        // Create the reply record
        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);

        // Automatically set ticket to 'open' so admin sees activity
        $ticket->update(['status' => 'open']);

        // Clear the textarea
        $this->reset('message');

        // Dispatch browser event to trigger the "Scroll to Bottom" JS
        $this->dispatch('reply-sent');

        session()->flash('success', 'Your reply has been sent.');
    }

    public function render()
    {
        return view('livewire.tickets.show', [
            'ticket' => $this->getTicket()
        ]);
    }
}