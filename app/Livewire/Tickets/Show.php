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

    /**
     * mount() runs once when the component is loaded.
     * We accept $id from the Blade view.
     */
    public function mount($id)
    {
        // Security Check: Verify ownership and existence
        $ticket = Ticket::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->first();

        if (!$ticket) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $this->ticketId = $id;
    }

    /**
     * Validation rules for the message input.
     */
    protected $rules = [
        'message' => 'required|string|min:2|max:5000',
    ];

    /**
     * The Computed Property caches the ticket query.
     * Access it as $this->ticketProperty (PHP) or $ticketProperty (Blade).
     */
    #[Computed]
    public function ticketProperty()
    {
        return Auth::user()->tickets()
            ->with(['replies.user'])
            ->findOrFail($this->ticketId);
    }

    /**
     * Handles the submission of a new reply.
     */
    public function sendReply()
    {
        $this->validate();

        // Access computed property (no parentheses)
        $ticket = $this->ticketProperty;

        if ($ticket->status === 'closed') {
            session()->flash('error', 'This ticket is closed and cannot receive replies.');
            return;
        }

        // Create the new reply record
        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);

        // Re-open ticket if it was pending or answered
        $ticket->update(['status' => 'open']);

        /**
         * CLEAR CACHE: 
         * This forces the #[Computed] method to re-run during render()
         * so the user sees their new message immediately.
         */
        unset($this->ticketProperty);

        // Reset the textarea input
        $this->reset('message');

        // Dispatch browser event for JS scrolling
        $this->dispatch('reply-sent');

        session()->flash('success', 'Your reply has been sent.');
    }

    public function render()
    {
        return view('livewire.tickets.show', [
            // Pass the computed property to the view
            'ticket' => $this->ticketProperty 
        ]);
    }
}