<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    // Show list of partners
    public function index()
    {
        $partners = Partner::latest()->get();
        return view('admin.manage.partners', compact('partners'));
    }

    // Show create form
    public function create()
    {
        return view('admin.create.partner');
    }

    // Store new partner
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partners', 'public');
        }

        Partner::create([
            'organization_id' => 1, // hardcoded since only one organization
            'name' => $request->name,
            'website' => $request->website,
            'logo' => $logoPath,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner added successfully.');
    }

    // Show edit form
    public function edit(Partner $partner)
    {
        return view('admin.edit.partner', compact('partner'));
    }

    // Update partner
    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $partner->logo = $request->file('logo')->store('partners', 'public');
        }

        $partner->update([
            'name' => $request->name,
            'website' => $request->website,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    // Delete partner
    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner deleted successfully.');
    }
}
