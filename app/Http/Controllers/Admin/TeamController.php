<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Organization;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $teams = Team::with('profilePhoto', 'organization')->latest()->paginate(10);
        return view('admin.manage.team', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create.team');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:teams,email',
            'phone' => 'nullable|string|max:20|unique:teams,phone',
            'bio' => 'nullable|string',
            'facebook' => 'nullable|url|unique:teams,facebook',
            'linkedin' => 'nullable|url|unique:teams,linkedin',
            'twitter' => 'nullable|url|unique:teams,twitter',
            'status' => 'nullable|string|in:active,inactive',
            'photo' => 'image|max:2048',
        ]);

        // Assign organization_id automatically since there's only one
        $validated['organization_id'] = 1;

        // Create team member
        $team = Team::create($validated);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('teams', 'public');

            $team->profilePhoto()->create([
                'file_path' => $filePath,
                'caption' => $request->name
            ]);
        }

        return redirect()->route('admin.team.index')->with('success', 'Team member added successfully.');
    }

    /**
     * Display the specified resource.
     */
   public function show(Team $team)
    {
        $team->load('profilePhoto');
        return view('admin.show.team', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
       $team->load('profilePhoto');
        return view('admin.edit.team', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:teams,email,' . $team->id,
            'phone' => 'nullable|string|max:20|unique:teams,phone,' . $team->id,
            'bio' => 'nullable|string',
            'facebook' => 'nullable|url|unique:teams,facebook,' . $team->id,
            'linkedin' => 'nullable|url|unique:teams,linkedin,' . $team->id,
            'twitter' => 'nullable|url|unique:teams,twitter,' . $team->id,
            'status' => 'nullable|string|in:active,inactive',
            'photo' => 'nullable|image|max:2048',
        ]);

        $team->update($validated);

        // Handle new photo if uploaded
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($team->profilePhoto) {
                Storage::disk('public')->delete($team->profilePhoto->file_path);
                $team->profilePhoto->delete();
            }

            $path = $request->file('photo')->store('teams', 'public');

            $team->profilePhoto()->create([
                'file_path' => $path,
                'caption' => $team->name,
            ]);
        }

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Team $team)
    {
       if ($team->profilePhoto) {
        Storage::disk('public')->delete($team->profilePhoto->file_path);
        $team->profilePhoto->delete();
    }

    $team->delete();

        return redirect()->route('admin.team.index')->with('success', 'Team member deleted successfully.');
    }
}
