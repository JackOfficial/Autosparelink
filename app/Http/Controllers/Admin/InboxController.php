<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InboxController extends Controller
{
    /**
     * Display the list of incoming contact messages.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'active'); // Default to active messages
        
        $messages = Contact::where('status', $status)
            ->when($request->search, function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('message', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(15);

        return view('admin.mailbox.index', compact('messages', 'status'));
    }

    /**
     * View a specific message and mark as read/resolved if needed.
     */
    public function show($id)
    {
        $message = Contact::findOrFail($id);
        
        return view('admin.mailbox.read', compact('message'));
    }

    /**
     * Update the status of a message (Resolve/Archive).
     */
    public function updateStatus(Request $request, $id)
    {
        $message = Contact::findOrFail($id);
        $message->update(['status' => $request->status]);

        return redirect()->route('admin.mailbox.index')
            ->with('success', 'Message status updated to ' . $request->status);
    }

    /**
     * Show the form to compose a new message or reply.
     */
    public function compose($id = null)
    {
        // If an ID is passed, we are replying to a specific message
        $replyTo = $id ? Contact::find($id) : null;
        
        return view('admin.mailbox.compose', compact('replyTo'));
    }

    /**
     * Send the email from the Admin panel.
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|min:3',
            'message' => 'required|string|min:5',
        ]);

        try {
            // Send the email to the user
            // We use the same ContactMail mailable, but you are the sender now
            Mail::to($request->email)->send(new ContactMail(
                name: 'Admin', // Or auth()->user()->name
                email: config('mail.from.address'),
                subjectText: $request->subject,
                messageContent: $request->message
            ));

            // If this was a reply, we can mark the original contact as 'resolved'
            if ($request->has('contact_id')) {
                Contact::where('id', $request->contact_id)->update(['status' => 'resolved']);
            }

            return redirect()->route('admin.mailbox.index')
                ->with('success', 'Email sent successfully to ' . $request->email);

        } catch (\Exception $e) {
            \Log::error("Admin Mail Send Error: " . $e->getMessage());
            return back()->with('error', 'Failed to send email. Check your SMTP settings.');
        }
    }

    /**
     * Move a message to trash (Soft Delete).
     */
    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        return redirect()->route('admin.mailbox.index')->with('success', 'Message moved to trash.');
    }
}