<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        return view('contact'); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $contact = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($contact);
        if($contact){
            $email = $request->email;
            $textMessage = $request->message;
            $toMail = "musengimanajacques@gmail.com";
            Mail::to($toMail)->send(new ContactMail($request->name, $email, $textMessage));
            return redirect()->back()->with('message', 'Your message was sent successfully');
        }
        else{
            return redirect()->back()->with('message', 'Your message could not be sent'); 
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
