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
    private function shopOrders()
    {
        $user = Auth::user();

        // Check if the user has a shop to prevent errors
        if (!$user->shop) {
            abort(403, 'No shop associated with this account.');
        }

        $shopId = $user->shop->id;

        return Order::whereHas('orderItems.part', function ($query) use ($shopId) {
            $query->where('shop_id', $shopId);
        });
    }

   public function index()
{
    // Only paginate the tickets for the Support Page
    $tickets = Auth::user()->tickets()->latest()->paginate(10);
    
    // If you need a dropdown for a "New Ticket" modal on the index page:
    $orders = $this->shopOrders()->latest()->take(10)->get();

    return view('shop.support.index', compact('tickets', 'orders'));
}

public function create()
{
    // Use your custom logic here to ensure they only see their specific orders
    $orders = $this->shopOrders()->latest()->take(20)->get();
    
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
