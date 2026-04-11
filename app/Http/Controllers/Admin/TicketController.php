<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Mail\TicketReplied;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'open');
        
        // Eager load 'user.shop' to identify if it's a vendor ticket
        $tickets = Ticket::with(['user.shop', 'order'])
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);

        return view('admin.tickets.index', compact('tickets', 'status'));
    }

    public function show(Ticket $ticket)
    {
        // Load everything needed for a full view
        $ticket->load(['user.shop', 'replies.user', 'photos', 'order.orderItems.part']);
        
        return view('admin.tickets.show', compact('ticket'));
    }

    public function storeReply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string|min:2'
        ]);

        $reply = $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        // Status 'pending' means Admin has spoken, waiting for User/Shop
        $ticket->update(['status' => 'pending']);

        try {
            Mail::to($ticket->user->email)->send(new TicketReplied($reply));
            $message = 'Reply sent and notification emailed.';
        } catch (\Exception $e) {
            \Log::error("Ticket Mail Error: " . $e->getMessage());
            $message = 'Reply saved (Email notification failed).';
        }

        return redirect()->back()->with('success', $message);
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status' => 'required|in:open,pending,closed']);

        $ticket->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Ticket is now ' . strtoupper($request->status));
    }
}