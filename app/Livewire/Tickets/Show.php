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
     * Using Route Model Binding directly in mount.
     * This prevents 'undefined variable' issues if passed correctly from the route.
     */
    public function mount(Ticket $ticket)
    {
        // Security Check: Ensure this ticket belongs to the logged-in user
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $this->ticketId = $ticket->id;
    }

    protected $rules = [
        'message' => 'required|string|min:2|max:5000',
    ];

    /**
     * Helper method to fetch ticket with eager-loaded replies
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

        $ticket = $this->getTicketProperty();

        if ($ticket->status === 'closed') {
            session()->flash('error', 'This ticket is closed and cannot receive replies.');
            return;
        }

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);

        $ticket->update(['status' => 'open']);

        $this->reset('message');
        $this->dispatch('reply-sent');

        session()->flash('success', 'Your reply has been sent.');
    }

    public function render()
    {
        return view('livewire.tickets.show', [
            'ticket' => $this->getTicketProperty()
        ]);
    }
}