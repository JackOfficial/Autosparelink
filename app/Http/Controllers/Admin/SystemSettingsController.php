<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting; // Assuming you have a Setting model

class SystemSettingsController extends Controller
{
    /**
     * Display the system settings page.
     */
    public function index()
    {
        // Fetch all settings as a key-value pair
        $settings = Setting::pluck('value', 'key')->all();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update system configurations.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'currency' => 'required|string|max:10',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        // 1. Handle Logo Upload
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('site', 'public');
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
        }

        // 2. Update Text Settings
        foreach ($request->except(['_token', 'site_logo']) as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }
}