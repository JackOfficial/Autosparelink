<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Mail\TicketReplied;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    /**
     * Display a listing of support tickets for autosparelink.com
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'open');
        
        $tickets = Ticket::with('user')
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('admin.tickets.index', compact('tickets', 'status'));
    }

    /**
     * Display the specific ticket details with conversation history.
     */
    public function show(Ticket $ticket)
    {
        // Load replies and the users who wrote them to avoid N+1 issues
        $ticket->load(['user', 'replies.user']);
        
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Store a reply and notify the customer via email.
     */
    public function storeReply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string|min:2'
        ]);

        // 1. Create the reply
        $reply = $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        // 2. Automatically set ticket to "pending" (Awaiting customer action)
        $ticket->update(['status' => 'pending']);

        // 3. Send the Mail Notification
        try {
            Mail::to($ticket->user->email)->send(new TicketReplied($reply));
            $message = 'Your reply has been sent and the customer has been notified.';
        } catch (\Exception $e) {
            // Log the error if mail fails, but still save the reply
            \Log::error("Mail failed for Ticket #{$ticket->id}: " . $e->getMessage());
            $message = 'Reply saved, but the email notification could not be sent.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Update ticket status (Close/Pending) manually via the sidebar.
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,pending,closed'
        ]);

        $ticket->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Ticket status updated to ' . ucfirst($request->status));
    }
}