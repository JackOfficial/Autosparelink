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
    // 1. Load basic relationships
    $shop->load([
        'user', 
        'documents', 
        'wallet', 
        'payouts' => function($query) {
            $query->latest()->take(5);
        }
    ]);

    // 2. Audited Counts (Parts & Completed Sales)
    $shop->loadCount([
        'parts' => fn($q) => $q->where('status', 'active'),
        'orderItems' => fn($q) => $q->where('status', 'completed')
    ]);

    // 3. Financial Audit (Mirroring PayoutController Logic)
    $percentage = $shop->commission_rate / 100;

    // Audited Gross Revenue: Items completed AND the Parent Order is completed
    $totalGross = $shop->orderItems()
        ->where('status', 'completed')
        ->whereHas('order', function ($query) {
            $query->where('status', 'completed');
        })
        ->sum(DB::raw('quantity * unit_price'));

    // Financial Breakdown
    $totalCommission = $totalGross * $percentage;
    $netEarnings = $totalGross - $totalCommission;

    // Payout Audit (Withdrawn vs Locked)
    $deductions = $shop->payouts()
        ->whereIn('status', ['completed', 'pending', 'processing'])
        ->selectRaw("
            SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_withdrawn,
            SUM(CASE WHEN status IN ('pending', 'processing') THEN amount ELSE 0 END) as total_locked
        ")
        ->first();

    $withdrawn = $deductions->total_withdrawn ?? 0;
    $locked = $deductions->total_locked ?? 0;
    $availableBalance = $netEarnings - ($withdrawn + $locked);

    // 4. Recent Sales for the Table
    $recentOrders = $shop->orderItems()
        ->whereNotIn('status', ['pending', 'cancelled'])
        ->with(['order.user', 'part'])
        ->latest()
        ->take(10)
        ->get();

    return view('admin.shops.show', [
        'shop'             => $shop,
        'recentOrders'     => $recentOrders,
        'totalGross'       => $totalGross,
        'totalCommission'  => $totalCommission,
        'netEarnings'      => $netEarnings,
        'totalWithdrawn'   => $withdrawn,
        'pendingPayouts'   => $locked,
        'availableBalance' => $availableBalance,
    ]);
}

public function viewDocument(Document $document)
{
    // Use the Storage facade to get the absolute path
    // This is safer than manual string concatenation
    if (!Storage::disk('local')->exists($document->file_path)) {
        abort(404, 'Document not found on server.');
    }

    $path = Storage::disk('local')->path($document->file_path);

    // Automatically detect MIME type if file_type isn't a full MIME (e.g., 'pdf' vs 'application/pdf')
    $mimeType = str_contains($document->file_type, '/') 
                ? $document->file_type 
                : $this->getMimeType($document->file_path);

    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . $document->title . '"'
    ]);
}

public function downloadDocument(Document $document)
{
    if (!Storage::disk('local')->exists($document->file_path)) {
        abort(404);
    }

    $path = Storage::disk('local')->path($document->file_path);
    
    // Get the original extension from the path to ensure the download works correctly
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    return response()->download($path, $document->title . '.' . $extension);
}

/**
 * Helper to ensure we have a valid MIME type for the browser
 */
private function getMimeType($path)
{
    return Storage::disk('local')->mimeType($path) ?? 'application/octet-stream';
}

/**
 * Approve a shop and make it live on the marketplace.
 */
public function approve(Shop $shop)
{
    DB::transaction(function () use ($shop) {
        $shop->update([
            'is_verified' => true, // This triggers the Wallet creation
            'is_active'   => true, // Make it visible
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
     * Toggle the active status (Suspending or Activating a shop).
     */
/**
 * Toggle the active status (Banning/Suspending vs Activating).
 */
public function toggleStatus(Shop $shop)
{
    $shop->update([
        'is_active' => !$shop->is_active
    ]);

    $status = $shop->is_active ? 'activated' : 'suspended/held';
    
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