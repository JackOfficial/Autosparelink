<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cause;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;

class CauseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $causes = Cause::with('mainPhoto')->orderBy('id', 'DESC')->get();
        return view('admin.manage.causes', compact('causes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create.cause');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
    'cause'       => 'required|string|max:255',
    'description' => 'nullable|string',
     'photo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048'
      ]);
    
          $cause = Cause::create([
    'name' => $validated['cause'],
    'description' => $validated['description'] ?? null,
        ]);

        if ($request->hasFile('photo')) {
    $cause->photos()->create([
        'file_path' => $request->file('photo')->store('causes', 'public'),
        'caption' => $request->input('cause')
    ]);
         }

return redirect()->route('admin.causes.index')->with('success', 'Cause added successfully!');
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
    public function edit(Cause $cause)
    {
        $cause->load('mainPhoto');
        return view('admin.edit.cause', compact('cause'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cause $cause)
    {
       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048'
        ]);
        
        $cause->update([
    'name' => $validated['name'],
    'description' => $validated['description'] ?? null,
        ]);
        
        if ($request->hasFile('photo')) {
            // Delete old main photo
            if ($cause->mainPhoto) {
                Storage::disk('public')->delete($cause->mainPhoto->file_path);
                $cause->mainPhoto()->delete();
            }

            // Add new photo
            $cause->photos()->create([
                'file_path' => $request->file('photo')->store('causes', 'public')
            ]);
        }
        
        return redirect()->route('admin.causes.index')->with('success', 'Cause updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cause $cause)
    {
        // Delete all related photos
        foreach ($cause->photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
        }

        $cause->delete();

        return redirect()->route('admin.causes.index')->with('success', 'Cause deleted successfully!');
    }
}
