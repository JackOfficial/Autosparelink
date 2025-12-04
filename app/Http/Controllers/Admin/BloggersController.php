<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bloggers;
use Inertia\Inertia;

class BloggersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloggers = Bloggers::all();
        return view('admin.manage.bloggers', compact('bloggers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create.blogger');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|max:13',
            'email' => 'required|email:unique|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $image_path = null; 

        if ($request->hasFile('photo')) {
            $image_path = $request->file('photo')->store('images/blogs', 'public');
             }

        $blogger = Bloggers::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'photo' => $image_path,
        ]);

        if($blogger){
            return redirect('/admin/bloggers')->with('message', 'Blogger saved successfully!');
        }
        else{
            return redirect()->back()->with('message', 'Blogger could not be saved. Try again!');
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
        $blogger = Bloggers::where('id', $id)
         ->first();
         return view('admin.edit.blogger', compact('blogger'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phone' => 'required|max:13',
            'email' => 'required|email:unique|string|max:255',
        ]);
        
        $image_path = null; 

        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048',
            ]); 
            $image_path = $request->file('photo')->store('images/blogs', 'public');
             }

             $blogger = Bloggers::where('id', $id)->update([
                'first_name' => $request->input('firstName'),
                'last_name' => $request->input('lastName'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'photo' => $image_path,
            ]);

        if($blogger){
            return redirect('/admin/bloggers')->with('message', 'Blogger updated successfully!');
        }
        else{
            return redirect()->back()->with('message', 'Blogger could not be updated. Try again!');
          }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blogger = Bloggers::where('id', $id)->delete();
        if($blogger){
          return redirect()->back()->with('message', 'Blogger deleted');
        }
        else{
            return redirect()->back()->with('message', 'Blogger could not be deleted');
        }
    }
}
