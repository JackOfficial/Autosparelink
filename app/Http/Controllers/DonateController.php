<?php

namespace App\Http\Controllers;

use App\Models\Donations;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DonateController extends Controller
{
    public function index(){
        return view('donation'); 
       }

       public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'card_number' => 'required',
            'cvv' => 'required',
            'expiry_date' => 'required',
            'amount' => 'required',
          ]);

          $donations = Donations::create([
              'card_name' => $request->input('name'),
              'card_number' => $request->input('card_number'),
              'cvv' => $request->input('cvv'),
              'expiry_date' => $request->input('expiry_date'),
              'amount' => $request->input('amount'),
          ]);

          if($donations){
            return redirect('donation.store')->with("message", "Thanks for donating!");
          }
          else{
            return redirect('donation.store')->with("message", "Your donation could not be sent, Try again!");
          }
        
       }
}
