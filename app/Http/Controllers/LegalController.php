<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class LegalController extends Controller
{
   /**
     * Policies: Typically needs a sidebar or table of contents.
     */
    public function policies()
    {
        $content = Page::where('slug', 'policies')->firstOrFail();
        return view('legal.policies', compact('content'));
    }

    /**
     * Terms: Professional, clean, document-heavy layout.
     */
    public function terms_and_conditions()
    {
        $content = Page::where('slug', 'terms-and-conditions')->firstOrFail();
        return view('legal.terms', compact('content'));
    }

    /**
     * FAQs: Interactive accordion-style layout.
     */
   public function faqs()
{
    $page = Page::where('slug', 'faqs')->firstOrFail();

    // If you store FAQs as a JSON string in the 'content' column, 
    // decode it so Alpine.js can read it as a Javascript object.
    $faqList = json_decode($page->content, true) ?? [];

    return view('legal.faqs', [
        'title' => $page->title,
        'faqs' => $faqList
    ]);
}
}
