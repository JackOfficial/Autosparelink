<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\BroadcastMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class BroadcastController extends Controller
{
    public function index()
    {
        return view('admin.broadcast.index');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'url'     => 'nullable|url',
            'type'    => 'required|in:promo,update,alert',
        ]);

        // Define the payload for the notification
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

        // Send to all users
        $users = User::all();
        Notification::send($users, new BroadcastMessage($payload));

        return back()->with('success', "Broadcast sent successfully to {$users->count()} users!");
    }
}