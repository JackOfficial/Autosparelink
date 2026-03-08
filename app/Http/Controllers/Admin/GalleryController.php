<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.manage.gallery');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create.gallery');
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    $request->validate([
        'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
    ]);

    $image_path = null; 
    $manager = new ImageManager(new Driver()); // GD driver

    if ($request->hasFile('photo')) {
        // Save original image into public storage
        $image_path = $request->file('photo')->store('images/gallery', 'public');

        // Absolute path to the stored image
        $absolutePath = storage_path('app/public/' . $image_path);

        // Make sure the thumbnails directory exists
        $thumbnailDir = storage_path('app/public/images/gallery_thumbnails');
        if (!file_exists($thumbnailDir)) {
            mkdir($thumbnailDir, 0777, true);
        }

        // ✅ Use read() instead of make()
        $image = $manager->read($absolutePath)->cover(500, 400);

        // Save thumbnail in thumbnails folder (keeping same filename)
        $thumbnailPath = $thumbnailDir . '/' . basename($image_path);
        $image->save($thumbnailPath);
    }

    $gallery = Gallery::create([
        'photo' => $image_path,
        'description' => $request->input('description'),
        'category' => 1
    ]);

    if ($gallery) {
        return redirect('/admin/gallery')->with('message', 'Photo uploaded successfully!');
    } else {
        return redirect()->back()->with('message', 'Photo could not be uploaded. Try again!');
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
        $gallery = Gallery::where('id', $id)->delete();
        if($gallery){
          return redirect()->back()->with('message', 'Photo has been deleted');
        }
        else{
            return redirect()->back()->with('message', 'Photo could not be deleted');
        }
    }
}
