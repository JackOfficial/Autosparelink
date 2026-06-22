<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Shop;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Store a newly created review via web form.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validation (Laravel automatically redirects back with errors if this fails)
        $validated = $request->validate([
            'reviewable_type' => 'required|in:part,shop',
            'reviewable_id'   => 'required|integer',
            'rating'          => 'required|integer|min:1|max:5',
            'comment'         => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        
        // 2. Convert client strings to full qualified Eloquent Class Names
        $modelMap = [
            'part' => Part::class,
            'shop' => Shop::class,
        ];
        
        $modelClass = $modelMap[$validated['reviewable_type']];
        $target = $modelClass::find($validated['reviewable_id']);

        if (!$target) {
            return back()->withErrors(['message' => 'Target entity not found.'])->withInput();
        }

        // 3. Strict Verified Purchase Guard Logic
        $hasPurchased = OrderItem::where('status', 'completed')
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where(function ($query) use ($validated, $target) {
                if ($validated['reviewable_type'] === 'part') {
                    $query->where('part_id', $target->id);
                } else {
                    // Check if they bought a part belonging to this shop
                    $query->whereHas('part', function ($partQuery) use ($target) {
                        $partQuery->where('shop_id', $target->id);
                    });
                }
            })->exists();

        if (!$hasPurchased) {
            return back()->withErrors([
                'message' => 'You can only review parts or shops from which you have completed purchases.'
            ])->withInput();
        }

        // 4. Prevent Double Entry & Save
        $target->reviews()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'status' => 'pending' // Admin moderation queue
            ]
        );

        // 5. Direct Web Redirect
        return back()->with('success', 'Thank you! Your review has been submitted and is awaiting moderation.');
    }
}