<?php

namespace App\Livewire;

use App\Mail\ContactMail;
use App\Models\Contact;
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

        // You can send email here if needed
        // Mail::to('your-email@example.com')->send(new ContactMail($this->name, $this->email, $this->subject, $this->message));

        // Clear form
        $this->reset(['name', 'email', 'subject', 'message']);

        // Show success message
        $this->successMessage = 'Your message has been sent successfully!';
    }

    public function render()
    {
        return view('livewire.contact-component');
    }
}
