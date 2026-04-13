<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit()
{
    $shop = auth()->user()->shop;

    // Safety check: Redirect if no shop exists
    if (!$shop) {
        return redirect()->route('shop.index')
            ->with('error', 'You need to create a shop before editing profile settings.');
    }

    return view('shop.profile.edit', compact('shop'));
}

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request)
{
    $shop = auth()->user()->shop;

    $request->validate([
        // Ensure name is unique but ignore the current shop ID
        'shop_name'    => 'required|string|max:255|unique:shops,shop_name,' . $shop->id,
        'description'  => 'nullable|string|max:1000',
        'phone_number' => 'required|string|max:20',
        'address'      => 'required|string|max:255',
        'tin_number'   => 'nullable|digits:9', 
        'logo'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    // Use specific keys to prevent mass-assignment vulnerabilities
    $data = $request->only(['shop_name', 'description', 'phone_number', 'address', 'tin_number']);

    // Handle Logo Upload
    if ($request->hasFile('logo')) {
        // Delete old logo from storage if it exists to save disk space
        if ($shop->logo && Storage::disk('public')->exists($shop->logo)) {
            Storage::disk('public')->delete($shop->logo);
        }
        
        $data['logo'] = $request->file('logo')->store('shops/logos', 'public');
    }

    $shop->update($data);

    return back()->with('success', 'Shop profile updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
