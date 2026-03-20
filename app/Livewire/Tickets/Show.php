<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class Show extends Component
{
    public $ticketId;
    public $message = '';

    public function mount($id)
    {
        // Security Check
        $ticket = Ticket::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->first();

        if (!$ticket) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $this->ticketId = $id;
    }

    protected $rules = [
        'message' => 'required|string|min:2|max:5000',
    ];

    /**
     * The Computed Property.
     * We use $this->ticket to access it internally.
     */
    #[Computed]
    public function ticket()
    {
        return Auth::user()->tickets()
            ->with(['replies.user'])
            ->findOrFail($this->ticketId);
    }

    public function sendReply()
    {
        $this->validate();

        // Access via $this->ticket (Computed property)
        $ticket = $this->ticket;

        if ($ticket->status === 'closed') {
            session()->flash('error', 'This ticket is closed.');
            return;
        }

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);

        $ticket->update(['status' => 'open']);

        /**
         * THE RELIABLE FIX:
         * Instead of unset(), use Livewire's built-in reset.
         * This clears the cache of the computed 'ticket' property.
         */
        $this->resetComputed('ticket');

        $this->reset('message');
        $this->dispatch('reply-sent');

        session()->flash('success', 'Your reply has been sent.');
    }

    public function render()
    {
        return view('livewire.tickets.show', [
            'ticket' => $this->ticket 
        ]);
    }
}