<?php

namespace App\Http\Controllers;

use App\Models\Volunteers;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\VolunteerApplicationSent;

class VolunteerController extends Controller
{
    protected  $messages = [
       'email.unique:volunteers,email' => 'You have already got registered!',
      ];
      
   public function index(){
    return Inertia::render('Volunteer'); 
   }

   public function store(Request $request)
   {
       $request->validate([
           'name' => 'required',
           'dob' => 'required',
           'email' => 'required|string|max:255|email|unique:volunteers,email',
           'phone' => 'required|string|max:255',
           'reason' => 'required|string|max:255'
       ]);

       $volunteer = Volunteers::create([
           'name' => $request->input('name'),
           'dob' => $request->input('dob'),
           'email' => $request->input('email'),
           'phone' => $request->input('phone'),
           'reason' => $request->input('reason'),
       ]);

       if($volunteer){
        Mail::to($request->email)->send(new VolunteerApplicationSent($request->name, $request->email));
           return to_route('volunteer')->with('message', 'Your request was sent successfully!');
       }
       else{
           return to_route('volunteer')->with('message', 'Something went wrong. Try again!');
         }
   }

}
