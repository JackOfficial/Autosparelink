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
            // 1. Save to Database so it shows up in your Admin Mailbox
            Contact::create([
                'name'    => $this->name,
                'email'   => $this->email,
                // Combines subject and message to fit your current migration
                'message' => "Subject: " . $this->subject . "\n\n" . $this->message,
                'status'  => 'active', 
            ]);

            // 2. Optional: Send Email Notification to yourself
            // Mail::to('admin@autosparelink.com')->send(new ContactMail(
            //     $this->name, $this->email, $this->subject, $this->message
            // ));

            // 3. Reset form fields
            $this->reset(['name', 'email', 'subject', 'message']);

            // 4. Show success message
            $this->successMessage = 'Your message has been sent successfully!';

        } catch (\Exception $e) {
            // Handle any database errors gracefully
            session()->flash('error', 'Something went wrong. Please try again later.');
        }
    }

    public function render()
    {
        return view('livewire.contact-component');
    }
}