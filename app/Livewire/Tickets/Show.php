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
    // 1. Validation matching your store() controller logic
    $validated = $this->validate([
        'message' => 'required|string|min:10',
    ]);

    $ticket = $this->ticket;

    // Security check: ensure ticket isn't closed
    if ($ticket->status == 'closed') {
        session()->flash('error', 'This ticket is closed.');
        return;
    }

    // 2. Create the Reply (using the relationship defined in your Ticket model)
    $ticket->replies()->create([
        'user_id' => auth()->id(),
        'message' => $validated['message'],
    ]);

    // 3. Update the Parent Ticket (Mirroring the store() controller defaults)
    // This ensures order_ref and other metadata stay linked and the ticket stays 'open'
    $ticket->update([
        'category'  => $ticket->category,
        'subject'   => $ticket->subject,
        'message'   => $ticket->message,
        'order_ref' => $ticket->order_ref,
        'status'    => 'open',   // Reset to open so support sees the new reply
        'priority'  => 'medium', // Keep the default medium priority
    ]);

    // 4. Cleanup
    unset($this->ticket); 
    $this->reset('message');
    
    // Dispatch event for Alpine.js auto-scroll
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