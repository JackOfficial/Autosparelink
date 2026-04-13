<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Document; // Or whatever your model name is
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends Controller
{
    /**
     * Display a listing of all shops (pending and active).
     */
    public function index()
    {
        $shops = Shop::with(['user', 'documents'])
            ->latest()
            ->paginate(15);

        return view('admin.shops.index', compact('shops'));
    }

    /**
     * Display the specific shop details and its verification documents.
     */
    public function show(Shop $shop)
    {
        $shop->load(['user', 'documents']);
        
        return view('admin.shops.show', compact('shop'));
    }

    public function viewDocument(Document $document)
{
    // Check if file exists on the 'local' or 'public' disk
    if (!Storage::disk('local')->exists($document->file_path)) {
        abort(404, 'Document not found on server.');
    }

    $path = storage_path('app/' . $document->file_path);

    // This displays the file in the browser (PDFs and Images)
    return response()->file($path, [
        'Content-Type' => $document->file_type,
        'Content-Disposition' => 'inline; filename="'.$document->title.'"'
    ]);
}

public function downloadDocument(Document $document)
{
    if (!Storage::disk('local')->exists($document->file_path)) {
        abort(404);
    }

    $path = storage_path('app/' . $document->file_path);

    // This forces the browser to download the file
    return response()->download($path, $document->title . '.' . $document->file_type);
}

    /**
     * Approve a shop and make it live on the marketplace.
     */
public function approve(Shop $shop)
{
    DB::transaction(function () use ($shop) {
        $shop->update([
            'is_active' => true,
            'approved_at' => now(),
        ]);

        $owner = $shop->user;

        // Consistent role name: 'seller'
        if (!$owner->hasRole('seller')) {
            $owner->assignRole('seller');
        }
    });

    return back()->with('success', "Shop '{$shop->shop_name}' approved. Owner is now a seller.");
}

    /**
     * Toggle the active status (Suspending or Activating a shop).
     */
   public function toggleStatus(Shop $shop)
{
    $shop->update([
        'is_active' => !$shop->is_active
    ]);

    $owner = $shop->user;

    if ($shop->is_active) {
        $owner->assignRole('seller');
        $status = 'activated';
    } else {
        $owner->removeRole('seller');
        $status = 'suspended';
    }
    
    return back()->with('success', "Shop has been {$status}.");
}

    /**
     * Show the form for editing the shop details.
     */
    public function edit(Shop $shop)
    {
        return view('admin.shops.edit', compact('shop'));
    }

    /**
     * Update shop information (e.g., fixing a typo or updating TIN).
     */
    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'shop_name'    => 'required|string|max:100|unique:shops,shop_name,' . $shop->id,
            'shop_email'   => 'required|email|unique:shops,shop_email,' . $shop->id,
            'phone_number' => 'required|string|max:20',
            'address'      => 'required|string|max:255',
            'tin_number'   => 'required|string|max:50',
        ]);

        $shop->update($validated);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop information updated successfully.');
    }

    /**
     * Remove the shop and potentially revoke the vendor role.
     */
 public function destroy(Shop $shop)
{
    DB::transaction(function () use ($shop) {
        $user = $shop->user;
        
        // Match the role name used in approve()
        if ($user->hasRole('seller')) {
            $user->removeRole('seller');
        }

        $shop->delete();
    });

    return redirect()->route('admin.shops.index')
        ->with('success', 'Shop removed and seller role revoked.');
}
}