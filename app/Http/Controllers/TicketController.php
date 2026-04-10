<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket; 
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display the user's support tickets.
     */
    public function index()
    {
        $tickets = Auth::user()->tickets()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('shop.tickets.index', compact('tickets'));
    }

    /**
     * Store a newly created ticket from the Modal.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'  => 'required|string|in:order,payment,part_request,technical',
            'subject'   => 'required|string|min:5|max:255',
            'message'   => 'required|string|min:10',
            'order_ref' => 'nullable|string|max:100', 
        ]);

        $ticket = Auth::user()->tickets()->create([
            'category'  => $validated['category'],
            'subject'   => $validated['subject'],
            'message'   => $validated['message'],
            'order_ref' => $validated['order_ref'],
            'status'    => 'open',
            'priority'  => 'medium',
        ]);

        return redirect()->back()->with('success', "Ticket #{$ticket->id} has been opened. Our team will review it shortly.");
    }

    /**
     * View a specific ticket conversation with replies.
     */
    public function show($id)
    {
        // Eager load replies and the user who wrote them
        $ticket = Auth::user()->tickets()
            ->with(['replies.user'])
            ->findOrFail($id);
        
        return view('shop.tickets.show', compact('ticket'));
    }

    /**
     * Allow the customer to reply to an existing ticket.
     */
    public function reply(Request $request, $id)
    {
        $ticket = Auth::user()->tickets()->findOrFail($id);

        // Security: Don't allow replies to closed tickets
        if ($ticket->status === 'closed') {
            return redirect()->back()->with('error', 'This ticket is closed and cannot receive replies.');
        }

        $request->validate([
            'message' => 'required|string|min:2'
        ]);

        // Create the reply
        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Update status to 'open' so admin knows there is a new response
        $ticket->update(['status' => 'open']);

        return redirect()->back()->with('success', 'Your reply has been sent.');
    }
}