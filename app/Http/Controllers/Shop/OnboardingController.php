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

        // If they already have a shop record, redirect them based on status
        if ($user->shop) {
            if ($user->shop->is_active) {
                return redirect()->route('shop.dashboard');
            }
            return view('user.onboarding.pending'); // A "waiting for approval" view
        }

        return view('shop.index');
    }

    // App\Http\Controllers\Shop\OnboardingController.php

public function registration_status()
{
    $user = auth()->user();

    // Safety: If they don't have a shop record, they shouldn't be here
    if (!$user->shop) {
        return redirect()->route('shop.register');
    }

    // If already active, send to dashboard
    if ($user->hasActiveShop()) {
        return redirect()->route('shop.dashboard');
    }

    return view('user.onboarding.pending');
}

    /**
     * Handle the shop registration submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shop_name'    => 'required|string|max:100|unique:shops,shop_name',
            'shop_email'   => 'required|email|unique:shops,shop_email',
            'phone_number' => 'required|string|max:20',
            'address'      => 'required|string|max:255',
            'description'  => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();

                // 1. Create the Shop record
                // We use updateOrCreate just in case a soft-deleted record exists
                $user->shop()->create([
                    'shop_name'    => $request->shop_name,
                    'slug'         => Str::slug($request->shop_name),
                    'shop_email'   => $request->shop_email,
                    'phone_number' => $request->phone_number,
                    'address'      => $request->address,
                    'description'  => $request->description,
                    'is_active'    => false, // Pending admin approval
                ]);

                // 2. Assign the 'shop' role via Spatie
                // This allows them to access the shop-specific areas but 
                // your middleware should still check 'is_active' for the dashboard
                if (!$user->hasRole('shop')) {
                    $user->assignRole('shop');
                }
            });

            // Redirect to the dedicated success/pending status page
            return redirect()->route('shop.register.success')
                ->with('success', 'Application submitted successfully.');

        } catch (\Exception $e) {
            // Log the error if needed: \Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while processing your application. Please try again.');
        }
    }
}