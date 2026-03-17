<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Broadcast; // Your new history model
use App\Notifications\BroadcastMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class BroadcastController extends Controller
{
    public function index()
    {
        // Fetch all sent broadcasts for the history table
        $history = Broadcast::latest()->get();
        
        return view('admin.broadcast.index', compact('history'));
    }

    public function show(Broadcast $broadcast)
{
    return view('admin.broadcast.show', compact('broadcast'));
}

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'url'     => 'nullable|url',
            'type'    => 'required|in:promo,update,alert',
        ]);

        $users = User::all();

        // 1. Store the broadcast in the database for future reference
        Broadcast::create([
            'type'            => $validated['type'],
            'message'         => $validated['message'],
            'url'             => $validated['url'],
            'recipient_count' => $users->count(),
        ]);

        // 2. Define the payload for the live notifications
        $payload = [
            'message' => $validated['message'],
            'url'     => $validated['url'],
            'type'    => $validated['type'],
            'icon'    => match($validated['type']) {
                'promo'  => 'fas fa-tag',
                'alert'  => 'fas fa-exclamation-triangle',
                default  => 'fas fa-bullhorn',
            }
        ];

        // 3. Send to all users via the database notification system
        Notification::send($users, new BroadcastMessage($payload));

        return back()->with('success', "Broadcast sent successfully to {$users->count()} users!");
    }

    public function destroy(Broadcast $broadcast)
{
    // This deletes the history record. 
    // Note: It won't delete the notifications already sent to users' dashboards.
    $broadcast->delete();

    return redirect()->route('broadcast.index')
                     ->with('success', 'Broadcast record deleted from history.');
}

public function clearAll()
{
    // This deletes all records in the broadcasts table
    Broadcast::truncate();

    return redirect()->route('admin.broadcast.index')
                     ->with('success', 'Broadcast history has been completely cleared.');
}

}