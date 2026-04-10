<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Auth::user()->tickets()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('shop.support.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        // Get orders linked to this shop so the vendor can select one
        $orders = Auth::user()->shop->orders()
            ->latest()
            ->take(20)
            ->get();

        return view('shop.support.create', compact('orders'));
    }

    /**
     * Store a newly created ticket.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'  => 'required|string',
            'priority'  => 'required|string|in:low,medium,high',
            'subject'   => 'required|string|min:5|max:255',
            'message'   => 'required|string|min:10',
            'order_id'  => 'nullable|exists:orders,id', 
            'photos.*'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Create the ticket using the relationship
        $ticket = Auth::user()->tickets()->create([
            'category'  => $validated['category'],
            'subject'   => $validated['subject'],
            'message'   => $validated['message'],
            'order_id'  => $validated['order_id'] ?? null,
            'priority'  => $validated['priority'],
            'status'    => 'pending',
        ]);

        // Handle MorphMany Photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('tickets/attachments', 'public');
                $ticket->photos()->create([
                    'path' => $path,
                    // 'user_id' => Auth::id() // Only if your photos table needs this
                ]);
            }
        }

        return redirect()->route('shop.support.index')
            ->with('success', "Ticket #{$ticket->id} has been opened successfully.");
    }

    public function show($id)
    {
        $ticket = Auth::user()->tickets()
            ->with(['replies.user', 'photos', 'order'])
            ->findOrFail($id);
        
        return view('shop.support.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = Auth::user()->tickets()->findOrFail($id);

        if ($ticket->status === 'closed') {
            return redirect()->back()->with('error', 'This ticket is closed.');
        }

        $request->validate([
            'message' => 'required|string|min:2'
        ]);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Status is automatically updated to 'pending' via your TicketReply booted() method
        
        return redirect()->back()->with('success', 'Your reply has been sent.');
    }
}