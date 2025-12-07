<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransmissionType;

class TransmissionTypeController extends Controller
{
    // Display all
    public function index()
    {
        $transmissionTypes = TransmissionType::latest()->get();
        return view('admin.transmission-types.index', compact('transmissionTypes'));
    }

    // Show create page
    public function create()
    {
        return view('admin.transmission-types.create');
    }

    // Store
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'gears_count' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        TransmissionType::create($request->only('name', 'gears_count', 'description'));

        return redirect()
            ->route('admin.transmission-types.index')
            ->with('success', 'Transmission Type created successfully.');
    }

    // Edit
    public function edit(TransmissionType $transmissionType)
    {
        return view('admin.transmission-types.edit', compact('transmissionType'));
    }

    // Update
    public function update(Request $request, TransmissionType $transmissionType)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'gears_count' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $transmissionType->update($request->only('name', 'gears_count', 'description'));

        return redirect()
            ->route('admin.transmission-types.index')
            ->with('success', 'Transmission Type updated successfully.');
    }

    // Delete
    public function destroy(TransmissionType $transmissionType)
    {
        $transmissionType->delete();

        return redirect()
            ->route('admin.transmission-types.index')
            ->with('success', 'Transmission Type deleted successfully.');
    }
}
