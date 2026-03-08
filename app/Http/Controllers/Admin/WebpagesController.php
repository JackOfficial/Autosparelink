<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pages;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WebpagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $webpages = Pages::all();
        return Inertia::render('Admin/Manage/Webpages', compact('webpages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return Inertia::render('Admin/Add/Webpage');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_name' => 'required',
            'title' => 'required',
            'text' => 'required',
            'link' => 'required',
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $image_path = null; 

        if ($request->hasFile('photo')) {
            $image_path = $request->file('photo')->store('images/pages', 'public');
             }

        $page = Pages::create([
            'page_name' => $request->input('page_name'),
            'header' => $request->input('title'),
            'text' => $request->input('text'),
            'link' => $request->input('link'),
            'photo' => $image_path,
        ]);

        if($page){
            return redirect('/admin/pages')->with('message', 'Page saved succefully!');
        }
        else{
            return redirect()->back()->with('message', 'Page could not be saved. Try again!');
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
        $page = Pages::findOrFail($id);
        return Inertia::render('Admin/Edit/Webpage', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'page_name' => 'required',
            'title' => 'required',
            'text' => 'required',
            'link' => 'required',
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
        ]);

        $image_path = null; 

        if ($request->hasFile('photo')) {
            $removePhoto = Pages::where('id', $id)->value('photo');
            unlink("storage/".$removePhoto);
            $image_path = $request->file('photo')->store('images/pages', 'public');
            $page = Pages::where('id', $id)->update([
                'page_name' => $request->input('page_name'),
                'header' => $request->input('title'),
                'text' => $request->input('text'),
                'link' => $request->input('link'),
                'photo' => $image_path,
            ]); 
        }
        else{
            $page = Pages::where('id', $id)->update([
                'page_name' => $request->input('page_name'),
                'header' => $request->input('title'),
                'text' => $request->input('text'),
                'link' => $request->input('link'),
            ]);
        }

        if($page){
            return redirect('/admin/pages')->with('message', 'Page updated succefully!');
        }
        else{
            return redirect()->back()->with('message', 'Page could not be updated. Try again!');
          }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
