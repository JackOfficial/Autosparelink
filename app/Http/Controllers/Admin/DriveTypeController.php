<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriveType;
use Illuminate\Http\Request;

class DriveTypeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $driveTypes = DriveType::latest()->get();
        return view('admin.drive-types.index', compact('driveTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.drive-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DriveType::create($request->only('name', 'description'));

        return redirect()->route('admin.drive-types.index')
                         ->with('success', 'Drive type created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DriveType $driveType)
    {
        return view('admin.drive-types.edit', compact('driveType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DriveType $driveType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $driveType->update($request->only('name', 'description'));

        return redirect()->route('admin.drive-types.index')
                         ->with('success', 'Drive type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DriveType $driveType)
    {
        $driveType->delete();

        return redirect()->route('admin.drive-types.index')
                         ->with('success', 'Drive type deleted successfully.');
    }
}
