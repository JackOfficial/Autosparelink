<?php

namespace App\Livewire;

use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactComponent extends Component
{
    public $name;
    public $email;
    public $subject;
    public $message;
    public $successMessage = '';

    protected $rules = [
        'name'    => 'required|string|min:3',
        'email'   => 'required|email',
        'subject' => 'required|string|min:3',
        'message' => 'required|string|min:10',
    ];

    public function submit()
    {
        $this->validate();

        try {
            // 1. Save to Database
            Contact::create([
                'name'    => $this->name,
                'email'   => $this->email,
                'message' => "Subject: " . $this->subject . "\n\n" . $this->message,
                'status'  => 'active', 
            ]);

            // 2. Notify the Admin
            // We pass the data to your ContactMail mailable
            Mail::to(config('mail.from.address'))->send(new ContactMail(
                $this->name, 
                $this->email, 
                $this->subject, 
                $this->message
            ));

            $this->reset(['name', 'email', 'subject', 'message']);
            $this->successMessage = 'Your message has been sent successfully!';

        } catch (\Exception $e) {
            // Log the error for your own debugging
            \Log::error("Mailbox Error: " . $e->getMessage());
            session()->flash('error', 'Something went wrong. Please try again later.');
        }
    }

    public function render()
    {
        return view('livewire.contact-component');
    }
}