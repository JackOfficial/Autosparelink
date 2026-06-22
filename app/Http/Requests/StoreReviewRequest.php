<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Path A Rule: Only authenticated users are allowed to review
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'reviewable_id' => ['required', 'integer'],
            'reviewable_type' => [
                'required', 
                'string', 
                Rule::in(['App\Models\Part', 'App\Models\Shop']) // Explicitly restrict valid review types
            ],
        ];
    }

    /**
     * Handle multi-column database unique validation check smoothly
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $exists = DB::table('reviews')
                ->where('user_id', auth()->id())
                ->where('reviewable_id', $this->reviewable_id)
                ->where('reviewable_type', $this->reviewable_type)
                ->exists();

            if ($exists) {
                $validator->errors()->add('rating', 'You have already submitted a review for this item.');
            }
        });
    }
}