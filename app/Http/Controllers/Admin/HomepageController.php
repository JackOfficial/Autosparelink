<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homepage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      dd("Hello world");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Add/Myhomepage');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'heading' => 'required',
            'caption' => 'required|string',
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            'link' => 'required|string',
        ]);
        
        $image_path = null; 

        if ($request->hasFile('photo')) {
            $image_path = $request->file('photo')->store('images/homepagephoto', 'public');
             }
             Homepage::where('status', 1)->update([
                'status'=> 0
             ]);
        $homepage = Homepage::create([
            'header' => $request->input('heading'),
            'caption' => $request->input('caption'),
            'photo' => $image_path,
            'link' => $request->input('link'),
            'status' => 1
        ]);

        if($homepage){
            return to_route('admin.myhomepage.index')->with("message", "Homepage posted successfully!");
        }
        else{
            return to_route('admin.myhomepage.index')->with("message", "Homepage could not be posted. Try again!");
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
