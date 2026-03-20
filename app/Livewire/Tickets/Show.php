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
    // 1. Validation: Exact match to your Controller's requirements
    $validated = $this->validate([
        'message' => 'required|string|min:10',
    ]);

    $ticket = $this->ticket;

    if ($ticket->status === 'closed') {
        session()->flash('error', 'This ticket is closed.');
        return;
    }

    // 2. Create the Reply (The "Conversation" part)
    $ticket->replies()->create([
        'user_id' => Auth::id(),
        'message' => $validated['message'],
    ]);

    /**
     * 3. Update the Parent Ticket
     * We use the exact same field array from your store() method 
     * to keep the data synchronized.
     */
    $ticket->update([
        'category'  => $ticket->category,  // Kept from original store()
        'subject'   => $ticket->subject,   // Kept from original store()
        'message'   => $ticket->message,   // Kept from original store()
        'order_ref' => $ticket->order_ref, // Kept from original store()
        'status'    => 'open',             // Exact match to controller default
        'priority'  => 'medium',           // Exact match to controller default
    ]);

    // 4. Refresh & UI Reset
    unset($this->ticket); 
    $this->reset('message');
    
    // Trigger Alpine.js scroll down
    $this->dispatch('reply-sent');

    session()->flash('success', "Your message has been sent. Our team will review it shortly.");
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