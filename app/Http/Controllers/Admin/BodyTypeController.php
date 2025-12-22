<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BodyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BodyTypeController extends Controller
{
    public function index()
    {
        // $bodyTypes = BodyType::all();
        // return view('admin.body-types.index', compact('bodyTypes'));
    }

    public function create()
    {
        return view('admin.body-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $iconPath = null;

        if ($request->hasFile('icon_url')) {
            $iconPath = $request->file('icon_url')->store('body-types', 'public');
        }

        BodyType::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon_url' => $iconPath,
        ]);

        return redirect()->route('admin.body-types.index')->with('success', 'Body type created successfully.');
    }

    public function edit(BodyType $bodyType)
    {
        return view('admin.body-types.edit', compact('bodyType'));
    }

    public function update(Request $request, BodyType $bodyType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('icon_url')) {
            if ($bodyType->icon_url) {
                Storage::disk('public')->delete($bodyType->icon_url);
            }
            $bodyType->icon_url = $request->file('icon_url')->store('body-types', 'public');
        }

        $bodyType->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.body-types.index')->with('success', 'Body type updated successfully.');
    }

    public function destroy(BodyType $bodyType)
    {
        if ($bodyType->icon_url) {
            Storage::disk('public')->delete($bodyType->icon_url);
        }

        $bodyType->delete();
        return redirect()->route('admin.body-types.index')->with('success', 'Body type deleted successfully.');
    }
}
