<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        'tin_number'      => 'nullable|string|max:50', 
        'logo'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        // Document validation
        'rdb_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'tin_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'owner_id'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    DB::transaction(function () use ($request, $shop) {
        // 1. Update basic shop info
        $shop->update([
            'shop_name'    => $request->shop_name,
            'slug'         => Str::slug($request->shop_name),
            'description'  => $request->description,
            'phone_number' => $request->phone_number,
            'address'      => $request->address,
            'tin_number'   => $request->tin_number,
        ]);

        // 2. Handle Logo (Public Disk)
        if ($request->hasFile('logo')) {
            if ($shop->logo && Storage::disk('public')->exists($shop->logo)) {
                Storage::disk('public')->delete($shop->logo);
            }
            $shop->logo = $request->file('logo')->store('shops/logos', 'public');
            $shop->save();
        }

        // 3. Handle Verification Documents (Polymorphic & Local Disk)
        $documentsToProcess = [
            'rdb_certificate' => 'RDB Certificate',
            'tin_certificate' => 'VAT/TIN Certificate',
            'owner_id'        => 'Owner ID / Passport',
        ];

        foreach ($documentsToProcess as $inputKey => $documentTitle) {
            if ($request->hasFile($inputKey)) {
                $file = $request->file($inputKey);

                // Find existing document record to delete old file
                $existingDoc = $shop->documents()->where('title', $documentTitle)->first();

                if ($existingDoc && Storage::disk('local')->exists($existingDoc->file_path)) {
                    Storage::disk('local')->delete($existingDoc->file_path);
                }

                // Store new file on local disk
                $path = $file->store('shop_verification/' . $shop->id, 'local');

                // Update existing record or create new one
                $shop->documents()->updateOrCreate(
                    ['title' => $documentTitle],
                    [
                        'file_path'   => $path,
                        'file_type'   => $file->getClientOriginalExtension(),
                        'file_size'   => $file->getSize(),
                        'uploaded_by' => auth()->id(),
                    ]
                );
            }
        }
    });

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
