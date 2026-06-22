<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Shop;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reviewable_type' => 'required|in:part,shop',
            'reviewable_id'   => 'required|integer',
            'rating'          => 'required|integer|min:1|max:5',
            'comment'         => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        
        // 1. Convert client strings to full qualified Eloquent Class Names
        $modelMap = [
            'part' => Part::class,
            'shop' => Shop::class,
        ];
        
        $modelClass = $modelMap[$validated['reviewable_type']];
        $target = $modelClass::find($validated['reviewable_id']);

        if (!$target) {
            return response()->json(['message' => 'Target entity not found.'], 404);
        }

        // 2. Strict Verified Purchase Guard Logic
        $hasPurchased = OrderItem::where('status', 'completed')
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where(function ($query) use ($validated, $target) {
                if ($validated['reviewable_type'] === 'part') {
                    $query->where('part_id', $target->id);
                } else {
                    // Safe verification: Check if they bought a part belonging to this shop
                    $query->whereHas('part', function ($partQuery) use ($target) {
                        $partQuery->where('shop_id', $target->id); // assuming part has shop_id
                    });
                }
            })->exists();

        if (!$hasPurchased) {
            return response()->json([
                'message' => 'You can only review parts or shops from which you have completed purchases.'
            ], 403);
        }

        // 3. Prevent Double Entry (updateOrCreate will seamlessly match your migration unique key)
        $review = $target->reviews()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'status' => 'pending' // Admin must moderate auto parts comments
            ]
        );

        return response()->json([
            'message' => 'Your review has been submitted and is awaiting moderation.',
            'review' => $review
        ], 201);
    }

    public function storeWeb(Request $request)
    {
        $response = $this->store($request);
        
        if ($response->getStatusCode() !== 201) {
            // Fix: Catch the decoded message array accurately
            $responseData = json_decode($response->getContent(), true);
            return back()->withErrors(['message' => $responseData['message'] ?? 'An error occurred.'])->withInput();
        } 

        return back()->with('success', 'Thank you! Your review is awaiting admin approval.');
    }
}