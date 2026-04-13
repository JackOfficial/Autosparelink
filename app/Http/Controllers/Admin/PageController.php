<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
   public function index() {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function edit($id) {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id) {
        $page = Page::findOrFail($id);
        
        // If it's FAQ, encode the input array to JSON
        if ($page->slug === 'faqs') {
            $page->content = json_encode($request->faq_data);
        } else {
            $page->content = $request->content;
        }

        $page->save();
        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully!');
    }
}
