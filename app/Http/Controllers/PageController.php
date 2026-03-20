<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cause;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\Photo;
use App\Models\Project;
use App\Models\Team;
use App\Models\Event;
use App\Models\News;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Story;
use App\Models\Organization;

class PageController extends Controller
{
    function index(){
        return view('index'); 
    }

    function about(){
        return view('about'); 
    }

    function search($keyword){
        $blogs = Blog::join('blog_categories', 'blogs.blog_category_id', 'blog_categories.id')
        ->join('bloggers', 'blogs.blogger_id', 'bloggers.id')
        ->select('blogs.*', 'blog_categories.blog_category', 'bloggers.first_name', 'bloggers.last_name')
        ->where('blogs.title', 'like', '%' . $keyword .'%')
        ->paginate(6);
        return Inertia::render('Blogs', compact('blogs', 'keyword')); 
    }
   
public function blogs()
{
    // Changed 'blogCategory' to 'category' to match your model
    $blogs = Blog::with(['category', 'blogPhoto']) 
        ->latest() 
        ->paginate(6);

    $latestNews = News::with(['category', 'newsPhoto'])
        ->latest()
        ->limit(3)
        ->get();

    // We also need to fetch categories for the sidebar
    $categories = BlogCategory::withCount(['blogs', 'news'])->get();

    return view('blogs', compact('blogs', 'latestNews', 'categories'));
}

    function news(){
        return view('news'); 
    }

     function articles(){
        return view('articles'); 
    }

      function policies(){
        return view('policies'); 
    }

    function terms_and_conditions(){
       return view('terms-and-conditions'); 
    }

     function faqs(){
        return view('faqs'); 
    }

      function cart(){
        return view('cart'); 
    }

    function news_details($id){
        return view('news-details');
    }

 public function article($slug)
{
    // 1. Fetch the blog post by slug with its category and polymorphic photo
    // We use firstOrFail() so it automatically shows a 404 if the slug is wrong
    $post = Blog::with(['category', 'blogPhoto', 'user'])
        ->where('slug', $slug)
        ->firstOrFail();

    // 2. Fetch categories with counts for the sidebar
    $categories = BlogCategory::withCount('blogs')->get();

    // 3. Fetch recent News updates for the sidebar "Latest Updates" section
    $recentPosts = Blog::with(['category', 'blogPhoto'])
        ->latest()
        ->limit(5)
        ->get();

    // 4. Increment the view count for this specific article
    $post->increment('views');

    return view('article-details', compact('post', 'categories', 'recentPosts'));
}

    function deleteComment($id){
        Comment::where('id', $id)->delete();
        return redirect()->back();
    }
    function blog($title){

        return view('blog'); 
    }

  public function post(Request $request)
{
    // 1. Validate both the comment and the blog existence
    $request->validate([
        'comment' => 'required|string|max:1000',
        'blog_id' => 'required|exists:blogs,id' 
    ]);

    // 2. Check if user is logged in to prevent 'id on null' errors
    if (!auth()->check()) {
        return redirect()->back()->with('error', 'You must be logged in to comment.');
    }

    // 3. Create the comment
    $comment = Comment::create([
        'user_id' => auth()->id(), // Shorter way to get the ID
        'comment' => $request->comment,
        'blog_id' => $request->blog_id,
    ]);

    // 4. IMPORTANT: You MUST return the redirect
    if ($comment) {
        return redirect()->back()->with('message', 'Your comment has been posted!');
    }

    return redirect()->back()->with('error', 'Your comment could not be posted!');
}

    function application_sent(){
        return Inertia::render('ApplicationSent');  
    }
    
}
