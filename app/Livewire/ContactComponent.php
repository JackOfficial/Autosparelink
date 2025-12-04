<?php

namespace App\Livewire;

use App\Mail\ContactMail;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactComponent extends Component
{
    public $name, $email, $subject, $message;

    public function contact(){
        $contact = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($contact);
        if($contact){
            // $email = $this->email;
            // $textMessage = $this->message;
            $this->reset();
            // $toMail = "musengimanajacques@gmail.com";
            // Mail::to($toMail)->send(new ContactMail($this->name, $email, $textMessage));
            session()->flash('contactSuccess', 'Your message was sent successfully');
        }
        else{
            session()->flash('contactFail', 'Your message could not be sent'); 
        }
    }
    public function render()
    {
        return view('livewire.contact-component');
    }
}
