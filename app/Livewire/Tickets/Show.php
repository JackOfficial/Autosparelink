<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed; // <--- 1. Add this import

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
            abort(403);
        }

        $this->ticketId = $id;
    }

    protected $rules = [
        'message' => 'required|string|min:2|max:5000',
    ];

    /**
     * 2. Add the #[Computed] attribute here.
     * This allows you to call $this->ticketProperty in PHP 
     * or $ticketProperty in your Blade file.
     */
    #[Computed]
    public function ticketProperty()
    {
        return Auth::user()->tickets()
            ->with(['replies.user'])
            ->findOrFail($this->ticketId);
    }

 public function sendReply()
{
    $this->validate();

    // Get the ticket from the computed property
    $ticket = $this->ticketProperty;

    if ($ticket->status === 'closed') {
        session()->flash('error', 'This ticket is closed.');
        return;
    }

    // Create the new reply
    $ticket->replies()->create([
        'user_id' => Auth::id(),
        'message' => $this->message,
    ]);

    // Update ticket status
    $ticket->update(['status' => 'open']);

    /**
     * CRITICAL FIX: Unset the computed property cache.
     * This forces Livewire to run the database query again 
     * during render() so your new message appears instantly.
     */
    unset($this->ticketProperty);

    // Clear input and trigger scroll
    $this->reset('message');
    $this->dispatch('reply-sent');

    session()->flash('success', 'Your reply has been sent.');
}

    public function render()
    {
        return view('livewire.tickets.show', [
            // Use the computed property here
            'ticket' => $this->ticketProperty 
        ]);
    }
}