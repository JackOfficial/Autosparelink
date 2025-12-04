<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organization = Organization::all();
       return view('admin.manage.organization', compact('organization'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create.organization');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'about' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'name', 'mission', 'vision', 'about', 
            'email', 'phone', 'address', 'website'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('organizations', 'public'); // saves in storage/app/public/organizations
            $data['logo'] = $path;
        }

        $organization = Organization::create($data);

        // If you want to attach additional photos (polymorphic)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photoFile) {
                $photoPath = $photoFile->store('organizations/photos', 'public');
                $organization->photos()->create([
                    'filename' => $photoPath,
                ]);
            }
        }

        return redirect()->route('admin.organization.index')
                         ->with('success', 'Organization created successfully!');
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
    public function edit(Organization $organization)
    {
      return view('admin.edit.organization', compact('organization'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
      $request->validate([
            'name' => 'required|string|max:255',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'about' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'name', 'mission', 'vision', 'about', 
            'email', 'phone', 'address', 'website'
        ]);

        // Handle logo update
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
            }

            $file = $request->file('logo');
            $path = $file->store('organizations', 'public');
            $data['logo'] = $path;
        }

        $organization->update($data);

        return redirect()->route('admin.organization.index')
                         ->with('success', 'Organization updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
      if ($organization->logo) {
            Storage::disk('public')->delete($organization->logo);
        }

        $organization->delete();

        return redirect()->route('admin.organization.index')
                         ->with('success', 'Organization deleted successfully!');
    }
}
