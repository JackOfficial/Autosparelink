<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
     * Display the specific shop details using centralized audit logic.
     */
    public function show(Shop $shop)
    {
        // 1. Load relationships and counts efficiently
        $shop->load([
            'user', 
            'documents', 
            'wallet', 
            'payouts' => fn($q) => $q->latest()->take(5)
        ])->loadCount([
            'parts' => fn($q) => $q->where('status', 'active'),
            'orderItems' => fn($q) => $q->where('status', 'completed')
        ]);

        // 2. CENTRALIZED FINANCIAL AUDIT
        // This replaces the manual math and ensures Admin sees what the Seller sees.
        $audit = $shop->getFinancialAudit();

        // 3. Recent Sales for the Table
        $recentOrders = $shop->orderItems()
            ->whereNotIn('status', ['pending', 'cancelled'])
            ->with(['order.user', 'part'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.shops.show', array_merge($audit, [
            'shop'         => $shop,
            'recentOrders' => $recentOrders,
        ]));
    }

    /**
     * View a verification document (PDF/Image) inline.
     */
    public function viewDocument(Document $document)
    {
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'Document not found.');
        }

        $path = Storage::disk('local')->path($document->file_path);
        $mimeType = $this->getMimeType($document->file_path);

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->title . '"'
        ]);
    }

    /**
     * Download a verification document.
     */
    public function downloadDocument(Document $document)
    {
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404);
        }

        $path = Storage::disk('local')->path($document->file_path);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return response()->download($path, $document->title . '.' . $extension);
    }

    /**
     * Approve a shop and grant the seller role.
     */
    public function approve(Shop $shop)
    {
        DB::transaction(function () use ($shop) {
            $shop->update([
                'is_verified' => true,
                'is_active'   => true,
                'approved_at' => now(),
            ]);

            $owner = $shop->user;
            if (!$owner->hasRole('seller')) {
                $owner->assignRole('seller');
            }
        });

        return back()->with('success', "Shop '{$shop->shop_name}' is now verified and active.");
    }

    /**
     * Toggle the active status (Suspend or Activate).
     */
    public function toggleStatus(Shop $shop)
    {
        $shop->update(['is_active' => !$shop->is_active]);
        $status = $shop->is_active ? 'activated' : 'suspended';
        
        return back()->with('success', "Shop has been {$status}.");
    }

    /**
     * Update shop information.
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
     * Delete the shop and revoke the seller role.
     */
    public function destroy(Shop $shop)
    {
        DB::transaction(function () use ($shop) {
            $user = $shop->user;
            if ($user->hasRole('seller')) {
                $user->removeRole('seller');
            }
            $shop->delete();
        });

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop removed and seller role revoked.');
    }

    /**
     * Private helper for MIME types.
     */
    private function getMimeType($path)
    {
        return Storage::disk('local')->mimeType($path) ?? 'application/octet-stream';
    }
}