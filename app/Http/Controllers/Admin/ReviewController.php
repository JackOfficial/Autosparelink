<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display a listing of pending reviews.
     */
    public function index(): View
    {
        // Fetch pending reviews with their polymorphic relations loaded
        $reviews = Review::with('reviewable')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Approve the specified review.
     */
    public function approve(Review $review): RedirectResponse
    {
        $review->update(['status' => 'approved']);

        return back()->with('success', 'The review has been approved and is now visible publically.');
    }

    /**
     * Reject and delete the specified review.
     */
    public function reject(Review $review): RedirectResponse
    {
        // You can either change status to 'rejected' or flat out delete it:
        $review->delete();

        return back()->with('success', 'The review has been rejected and removed.');
    }
}