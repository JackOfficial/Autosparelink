<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    /**
     * Show the shop registration form.
     */
    public function index()
    {
        $user = Auth::user();

        // If they already have a shop record, redirect to status or dashboard
        if ($user->shop) {
            return $user->hasActiveShop() 
                ? redirect()->route('shop.dashboard') 
                : redirect()->route('register.success');
        }

        return view('shop.index');
    }

    /**
     * Show the pending status page.
     */
    public function registration_status()
    {
        $user = Auth::user();

        if (!$user->shop) {
            return redirect()->route('shop.register');
        }

        if ($user->hasActiveShop()) {
            return redirect()->route('shop.dashboard');
        }

        // Updated view path to match your structure
        return view('user.onboarding.pending');
    }

    /**
     * Handle the shop registration submission including document uploads.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shop_name'    => 'required|string|max:100|unique:shops,shop_name',
            'shop_email'   => 'required|email|unique:shops,shop_email',
            'phone_number' => 'required|string|max:20',
            'address'      => 'required|string|max:255',
            'description'  => 'nullable|string|max:500',
            'tin_number'   => 'required|string|max:50',
            // Document Validation
            'rdb_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'owner_id'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();

                // 1. Create the Shop record
                $shop = $user->shop()->create([
                    'shop_name'    => $request->shop_name,
                    'slug'         => Str::slug($request->shop_name),
                    'shop_email'   => $request->shop_email,
                    'phone_number' => $request->phone_number,
                    'address'      => $request->address,
                    'description'  => $request->description,
                    'tin_number'   => $request->tin_number,
                    'is_active'    => false, 
                ]);

                // 2. Handle File Uploads (Polymorphic)
                if ($request->hasFile('rdb_certificate')) {
                    $this->uploadDocument($request->file('rdb_certificate'), 'RDB Certificate', $shop);
                }

                if ($request->hasFile('owner_id')) {
                    $this->uploadDocument($request->file('owner_id'), 'Owner ID / Passport', $shop);
                }

                // 3. Assign the 'shop' role via Spatie
                if (!$user->hasRole('shop')) {
                    $user->assignRole('shop');
                }
            });

            return redirect()->route('register.success')
                ->with('success', 'Application submitted successfully.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong. Please check your files and try again.');
        }
    }

    /**
     * Private helper to process polymorphic document uploads.
     */
    private function uploadDocument($file, $title, $shop)
    {
        // Store in a 'private' folder for security
        $path = $file->store('shop_verification/' . $shop->id, 'local');

        $shop->documents()->create([
            'title'       => $title,
            'file_path'   => $path,
            'file_type'   => $file->getClientOriginalExtension(),
            'file_size'   => $file->getSize(),
            'uploaded_by' => Auth::id(),
        ]);
    }
}