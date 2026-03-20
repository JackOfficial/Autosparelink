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
        $ticket = Ticket::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->first();

        if (!$ticket) {
            abort(403);
        }

        $this->ticketId = $id;
    }

    protected $rules = [
        'message' => 'required|string|min:2|max:5000',
    ];

    /**
     * Computed Property: ticket
     * Access as $this->ticket in PHP and $ticket in Blade.
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

        // 1. Get the ticket (using the computed property)
        $ticket = $this->ticket;

        if ($ticket->status === 'closed') {
            session()->flash('error', 'This ticket is closed.');
            return;
        }

        // 2. Save the reply
        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);

        $ticket->update(['status' => 'open']);

        /**
         * 3. REFRESH THE CACHE
         * This is the standard way to clear a computed property.
         * We use unset() on the property name.
         */
        unset($this->ticket);

        $this->reset('message');
        $this->dispatch('reply-sent');

        session()->flash('success', 'Your reply has been sent.');
    }

    /**
     * Delete a specific reply if it belongs to the authenticated user.
     */
  public function deleteReply($replyId)
{
    // Find the reply AND ensure it's less than 15 minutes old
    $reply = $this->ticket->replies()
        ->where('id', $replyId)
        ->where('user_id', Auth::id())
        ->where('created_at', '>=', now()->subMinutes(15))
        ->first();

    if ($reply) {
        $reply->delete();
        
        // Refresh the computed property cache to update the UI instantly
        unset($this->ticket);
        
        session()->flash('success', 'Message deleted successfully.');
    } else {
        // This handles cases where the user tries to delete after the 15-min window
        session()->flash('error', 'You can no longer delete this message (time limit exceeded).');
    }
}

    public function render()
    {
        return view('livewire.tickets.show', [
            'ticket' => $this->ticket 
        ]);
    }
}