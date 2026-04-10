<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        // Get only tickets belonging to this user
        $tickets = Auth::user()->tickets()->latest()->paginate(10);
        return view('shop.support.index', compact('tickets'));
    }

    public function create()
    {
        // Fetch the shop's orders so they can link a ticket to a specific sale
        $orders = Auth::user()->shop->orders()->latest()->take(20)->get();
        return view('shop.support.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string',
            'order_id' => 'nullable|exists:orders,id'
        ]);

        $ticket = Auth::user()->tickets()->create([
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'message' => $request->message,
            'order_id' => $request->order_id,
            'status' => 'pending'
        ]);

        // Handle Photo attachments (using your morphMany photos relationship)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('tickets/attachments', 'public');
                $ticket->photos()->create(['path' => $path]);
            }
        }

        return redirect()->route('shop.support.index')->with('success', 'Support request submitted.');
    }

    public function show(Ticket $ticket)
    {
        // Safety: Ensure shop only sees their own ticket
        if ($ticket->user_id !== Auth::id()) { abort(403); }

        $ticket->load(['replies.user', 'photos', 'order']);
        return view('shop.support.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) { abort(403); }

        $request->validate(['message' => 'required|string']);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        return back()->with('success', 'Reply sent.');
    }
}
