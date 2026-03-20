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

 public function news()
{
    // 1. Fetch published news with polymorphic photos and categories
    // We paginate(9) to create a clean 3x3 grid on the frontend
    $newsList = News::with(['newsPhoto', 'category', 'user'])
        ->where('status', 'published')
        ->latest()
        ->paginate(9);

    // 2. Fetch categories specifically for News to show in a sidebar or filter
    $categories = BlogCategory::where('type', 'news')
        ->withCount('news')
        ->get();

    // 3. Pass the data to the view
    return view('news', compact('newsList', 'categories'));
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

public function news_details($slug)
{
    // 1. Added 'comments' to eager loading to prevent "null" errors and N+1 issues
    $news = News::with(['newsPhoto', 'category', 'user', 'comments']) 
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    // 2. Fetch News Categories (filtered by type)
    $categories = BlogCategory::where('type', 'news')
        ->withCount('news')
        ->get();

    // 3. Fetch Recent News (Aliasing newsPhoto as blogPhoto for the sidebar Blade)
    $recentPosts = News::with(['newsPhoto as blogPhoto']) 
        ->where('id', '!=', $news->id)
        ->where('status', 'published')
        ->latest()
        ->limit(5)
        ->get();

    // 4. Increment view count
    $news->increment('views');

    return view('news-details', compact('news', 'categories', 'recentPosts'));
}

public function article($slug)
{
    // 1. Added 'comments' to eager loading to support the polymorphic relationship
    $post = Blog::with(['category', 'blogPhoto', 'user', 'comments'])
        ->where('slug', $slug)
        ->where('status', 'published') // Ensure only published posts are visible
        ->firstOrFail();

    // 2. Filter categories by 'blog' type so the sidebar is relevant
    $categories = BlogCategory::where('type', 'blog')
        ->withCount('blogs')
        ->get();

    // 3. Recent posts (excluding the current one)
    $recentPosts = Blog::with(['category', 'blogPhoto'])
        ->where('id', '!=', $post->id)
        ->latest()
        ->limit(5)
        ->get();

    // 4. Increment the view count
    $post->increment('views');

    return view('article-details', compact('post', 'categories', 'recentPosts'));
}

   public function deleteComment($id)
{
    // 1. Find the comment or fail with a 404
    $comment = Comment::findOrFail($id);

    // 2. Security Check: Only the owner or an admin can delete
    if (auth()->id() !== $comment->user_id && !auth()->user()->is_admin) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    // 3. Delete
    $comment->delete();

    return redirect()->back()->with('success', 'Comment deleted successfully.');
}

    function blog($title){

        return view('blog'); 
    }

public function post(Request $request)
{
    // 1. Validate - We check for blog_id OR news_id depending on where the user is
    $request->validate([
        'comment' => 'required|string|max:1000',
        'blog_id' => 'nullable|exists:blogs,id',
        'news_id' => 'nullable|exists:news,id',
    ]);

    // 2. Check Auth (You can also use the 'auth' middleware on the route instead)
    if (!auth()->check()) {
        return redirect()->back()->with('error', 'You must be logged in to comment.');
    }

    // 3. Determine which model we are commenting on
    if ($request->has('blog_id')) {
        $model = \App\Models\Blog::find($request->blog_id);
    } elseif ($request->has('news_id')) {
        $model = \App\Models\News::find($request->news_id);
    }

    if (!$model) {
        return redirect()->back()->with('error', 'Target content not found.');
    }

    // 4. Use the relationship to create the comment
    // This automatically sets commentable_id and commentable_type
    $comment = $model->comments()->create([
        'user_id' => auth()->id(),
        'comment' => $request->comment,
    ]);

    if ($comment) {
        return redirect()->back()->with('message', 'Your comment has been posted!');
    }

    return redirect()->back()->with('error', 'Your comment could not be posted!');
}

    function application_sent(){
        return Inertia::render('ApplicationSent');  
    }
    
}
