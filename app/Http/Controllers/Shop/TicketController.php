<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index()
    {
        // Fetch tickets belonging to the seller
        $tickets = Auth::user()->tickets()->latest()->paginate(10);
        
        // Fetch recent orders associated with this shop using the scope
        $orders = Order::forCurrentSeller()->latest()->take(10)->get();

        return view('shop.support.index', compact('tickets', 'orders'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        // Get shop-specific orders for the dropdown selection using the scope
        $orders = Order::forCurrentSeller()->latest()->take(20)->get();
        
        return view('shop.support.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject'  => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'message'  => 'required|string',
            'order_id' => 'nullable|exists:orders,id',
            'photos.*' => 'nullable|image|max:2048'
        ]);

        // Security: If an order_id is provided, verify it belongs to this shop
        if ($request->filled('order_id')) {
            Order::forCurrentSeller()->findOrFail($request->order_id);
        }

        $ticket = Auth::user()->tickets()->create([
            'subject'  => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'message'  => $request->message,
            'order_id' => $request->order_id,
            'status'   => 'open'
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('tickets/attachments', 'public');
                $ticket->photos()->create(['file_path' => $path]);
            }
        }

        return redirect()->route('shop.support.index')->with('success', 'Support request submitted.');
    }

    public function show(Ticket $ticket)
    {
        // Use relationship check for clean security
        if ($ticket->user_id != Auth::id()) { 
            abort(403); 
        }

        $ticket->load(['replies.user', 'photos', 'order']);
        
        return view('shop.support.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id != Auth::id()) { 
            abort(403); 
        }

        $request->validate(['message' => 'required|string']);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        $ticket->update(['status' => 'open']);

        return back()->with('success', 'Reply sent.');
    }
}