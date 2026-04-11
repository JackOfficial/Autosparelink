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
     * Private helper to fetch only orders containing parts from this shop.
     */
    private function shopOrders()
    {
        $user = Auth::user();

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
        // Paginate tickets for the vendor
        $tickets = Auth::user()->tickets()->latest()->paginate(10);
        
        // Fetch recent orders for the 'Quick Create' modal if present on index
        $orders = $this->shopOrders()->latest()->take(10)->get();

        return view('shop.support.index', compact('tickets', 'orders'));
    }

    public function create()
    {
        // Get shop-specific orders for the dropdown selection
        $orders = $this->shopOrders()->latest()->take(20)->get();
        
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

        $ticket = Auth::user()->tickets()->create([
            'subject'  => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'message'  => $request->message,
            'order_id' => $request->order_id,
            'status'   => 'open' // Set to 'open' initially as per migration enum
        ]);

        // Handle Photo attachments using MorphMany
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
        // Security: Ensure shop only sees their own ticket
        if ($ticket->user_id != Auth::id()) { 
            abort(403, 'Unauthorized access to this ticket.'); 
        }

        $ticket->load(['replies.user', 'photos', 'order']);
        
        return view('shop.support.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) { 
            abort(403); 
        }

        $request->validate(['message' => 'required|string']);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        // When the user replies, status returns to 'open' so admin knows it's their turn
        $ticket->update(['status' => 'open']);

        return back()->with('success', 'Reply sent.');
    }
}