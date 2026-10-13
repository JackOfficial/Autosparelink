<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Services\InTouchPaymentService;
use Illuminate\Support\Facades\Log;

class SystemSettingsController extends Controller
{
    protected $intouchService;

    public function __construct(InTouchPaymentService $intouchService)
    {
        $this->intouchService = $intouchService;
    }

    /**
     * Display the system settings page.
     */
   public function index()
    {
        // Safe fallback check if the gateway is unreachable
        try {
            $floatBalance = $this->intouchService->getBalance()['balance'] ?? 0;
        } catch (\Exception $e) {
            Log::warning("InTouch balance check failed on settings view: " . $e->getMessage());
            $floatBalance = 'Unavailable';
        }

        $settings = [
            'site_name'        => setting('site_name', 'Auto Spare Part'),
            'contact_email'    => setting('contact_email', 'admin@autosparepart.com'),
            'site_logo'        => setting('site_logo'),
            'balance'          => $floatBalance,
            'currency'         => setting('currency', 'RWF'), // Changed default to RWF based on your platform currency
            'maintenance_mode' => setting('maintenance_mode', '0'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update system configurations.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name'        => 'required|string|max:255',
            'contact_email'    => 'required|email',
            'site_logo'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'currency'         => 'required|string|max:10',
            'maintenance_mode' => 'nullable|in:0,1',
        ]);

        // 1. Handle Logo Upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo if it exists to save space on Namecheap
            $oldLogo = setting('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('site_logo')->store('site', 'public');
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
        }

        // 2. Update Text Settings
        // We exclude the logo and token to process only text/select inputs
        foreach ($request->only(['site_name', 'contact_email', 'currency', 'maintenance_mode']) as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }
}