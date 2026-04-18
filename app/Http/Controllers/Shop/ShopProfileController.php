<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function downloadDocument($id)
{
    // Ensure the document belongs to the authenticated user's shop
    $document = auth()->user()->shop->documents()->findOrFail($id);

    if (!Storage::disk('local')->exists($document->file_path)) {
        abort(404, 'File not found on server.');
    }

    return Storage::disk('local')->download(
        $document->file_path, 
        Str::slug($document->title) . '.' . $document->file_type
    );
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
        'shop_name'       => 'required|string|max:255|unique:shops,shop_name,' . $shop->id,
        'description'     => 'nullable|string|max:1000',
        'phone_number'    => 'required|string|max:20',
        'address'         => 'required|string|max:255',
        'tin_number'      => 'nullable|digits:9', 
        'logo'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        // Document validation
        'rdb_certificate' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
        'tin_certificate' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
        'owner_id'        => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
    ]);

    $data = $request->only(['shop_name', 'description', 'phone_number', 'address', 'tin_number']);

    // List of all possible file uploads
    $fileFields = ['logo' => 'shops/logos', 'rdb_certificate' => 'shops/docs', 'tin_certificate' => 'shops/docs', 'owner_id' => 'shops/docs'];

    foreach ($fileFields as $field => $path) {
        if ($request->hasFile($field)) {
            // Delete old file if it exists
            if ($shop->$field && Storage::disk('public')->exists($shop->$field)) {
                Storage::disk('public')->delete($shop->$field);
            }
            
            // Store the new file
            $data[$field] = $request->file($field)->store($path, 'public');
        }
    }

    $shop->update($data);

    return back()->with('success', 'Shop profile and documents updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
