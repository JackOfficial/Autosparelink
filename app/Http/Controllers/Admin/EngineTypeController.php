<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EngineType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EngineTypeController extends Controller
{
    public function index()
    {
        $engineTypes = EngineType::all();
        return view('admin.engine_types.index', compact('engineTypes'));
    }

    public function create()
    {
        return view('admin.engine_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $iconPath = $request->hasFile('icon_url') 
            ? $request->file('icon_url')->store('engine_types', 'public') 
            : null;

        EngineType::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon_url' => $iconPath,
        ]);

        return redirect()->route('admin.engine-types.index')->with('success', 'Engine type created successfully.');
    }

    public function edit(EngineType $engineType)
    {
        return view('admin.engine_types.edit', compact('engineType'));
    }

    public function update(Request $request, EngineType $engineType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('icon_url')) {
            if ($engineType->icon_url) {
                Storage::disk('public')->delete($engineType->icon_url);
            }
            $engineType->icon_url = $request->file('icon_url')->store('engine_types', 'public');
        }

        $engineType->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.engine-types.index')->with('success', 'Engine type updated successfully.');
    }

    public function destroy(EngineType $engineType)
    {
        if ($engineType->icon_url) {
            Storage::disk('public')->delete($engineType->icon_url);
        }

        $engineType->delete();

        return redirect()->route('admin.engine-types.index')->with('success', 'Engine type deleted successfully.');
    }
}
