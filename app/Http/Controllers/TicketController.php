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

        return view('user.tickets.index', compact('tickets'));
    }

    /**
     * Store a newly created ticket from the Modal.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'category'  => 'required|string|in:order,payment,part_request,technical',
            'subject'   => 'required|string|min:5|max:255',
            'message'   => 'required|string|min:10',
            'order_ref' => 'nullable|string|max:100', // Added for SMM order tracking
        ]);

        // 2. Creation
        // We assume the Ticket model has 'user_id' in $fillable or uses the relationship
        $ticket = Auth::user()->tickets()->create([
            'category'  => $validated['category'],
            'subject'   => $validated['subject'],
            'message'   => $validated['message'],
            'order_ref' => $validated['order_ref'],
            'status'    => 'open', // Default status
            'priority'  => 'medium',
        ]);

        // 3. Response
        // Redirecting back to the dashboard (or wherever the modal was triggered)
        return redirect()->back()->with('success', "Ticket #{$ticket->id} has been opened. Our team will review it shortly.");
    }

    /**
     * View a specific ticket conversation.
     */
    public function show($id)
    {
        $ticket = Auth::user()->tickets()->findOrFail($id);
        
        return view('user.tickets.show', compact('ticket'));
    }
}