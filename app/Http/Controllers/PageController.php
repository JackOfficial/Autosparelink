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
use App\Models\Page;
use App\Models\Partner;
use App\Models\Story;
use App\Models\Organization;

class PageController extends Controller
{
    function index(){
        
        // $blogs = Blog::with(['blogPhoto', 'cause', 'user', 'likes', 'comments'])->latest()->get();

        // $projects = Project::with('project_photo', 'cause')->latest()->get();

        // $current_project = Project::with('project_photo', 'cause')->latest()->first();

        // $events = Event::latest()->take(3)->get();

        // $causes = Cause::with('mainPhoto')->latest()->take(4)->get(); 

        // $partners = Partner::all();

        // $stories= Story::with(['organization', 'user', 'cause', 'photo'])->latest()->take(3)->get();

        // $header = Page::where('page_name', 'Home')->first();
        
        // $gallery = Photo::with('imageable')->latest()->take(6)->get();
        
        // $organization = Organization::first();

        return view('index'); 
    }

    function about(){
        return view('about'); 
    }
    
    function gallery(){
        $gallery = Gallery::orderBy('id', 'DESC')
        ->get();
        return view('gallery', compact('gallery')); 
    }

    function search($keyword){
        $blogs = Blogs::join('blog_categories', 'blogs.blog_category_id', 'blog_categories.id')
        ->join('bloggers', 'blogs.blogger_id', 'bloggers.id')
        ->select('blogs.*', 'blog_categories.blog_category', 'bloggers.first_name', 'bloggers.last_name')
        ->where('blogs.title', 'like', '%' . $keyword .'%')
        ->paginate(6);
        return Inertia::render('Blogs', compact('blogs', 'keyword')); 
    }
    function blogs(){
        return view('blogs'); 
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

    function blog_category($id){
        $blogs = Blogs::join('blog_categories', 'blogs.blog_category_id', 'blog_categories.id')
        ->join('bloggers', 'blogs.blogger_id', 'bloggers.id')
        ->where('blogs.blog_category_id', $id)
        ->select('blogs.*', 'blog_categories.blog_category', 'bloggers.first_name', 'bloggers.last_name')
        ->paginate(6);
        return Inertia::render('Blogs', compact('blogs')); 
     }

    function deleteComment($id){
        Comments::where('id', $id)->delete();
        return redirect()->back();
    }
    function blog($title){
        $blog = Blogs::join('blog_categories', 'blogs.blog_category_id', 'blog_categories.id')
        ->join('bloggers', 'blogs.blogger_id', 'bloggers.id')
        ->where('blogs.title', $title)
        ->select('blogs.*', 'blog_categories.blog_category', 'bloggers.first_name', 'bloggers.last_name')
        ->first();

        $latest_blogs= Blogs::join('blog_categories', 'blogs.blog_category_id', 'blog_categories.id')
        ->join('bloggers', 'blogs.blogger_id', 'bloggers.id')
        ->select('blogs.*', 'blog_categories.blog_category', 'bloggers.first_name', 'bloggers.last_name')
        ->get();

        $categories = Blog_categories::all();

        $related = Blogs::join('blog_categories', 'blogs.blog_category_id', 'blog_categories.id')
        ->join('bloggers', 'blogs.blogger_id', 'bloggers.id')
        ->select('blogs.*', 'blog_categories.blog_category', 'bloggers.first_name', 'bloggers.last_name')
        ->get();

        $comments = Comments::join('blogs', 'comments.blog_id', 'blogs.id')
        ->join('users', 'comments.user_id', 'users.id')
        ->where('comments.blog_id', $blog->id)
        ->select('comments.*', 'users.name', 'users.avatar')
        ->orderBy('comments.id', 'DESC')
        ->get();

        return view('blog', compact('blog', 'latest_blogs', 'related', 'categories', 'comments')); 
    }

    function donate(){
        return view('donation');  
    }

    function volunteer(){
        return view('volunteer');  
    }

    function post(Request $request){
        $request->validate([
          'comment' => 'required|string'
        ]);
        $comment = Comments::create([
            'user_id' => auth()->user()->id,
            'comment' => $request->comment,
            'blog_id' => $request->blogId,
        ]);
        if($comment){
           session()->flash('message', 'Your comment has been Posted!');
        }
        else{
            session()->flash('message', 'Your comment could not be posted!');
        }
    }

    function projects(){
        $projects = Projects::join('causes', 'projects.cause_id', 'causes.id')
        ->select('projects.*', 'causes.cause')
        ->orderBy('id', 'DESC')
        ->paginate(2);
        return view('projects', compact('projects')); 
    }

    function stories(){
        $stories = Stories::join('bloggers', 'stories.blogger_id', 'bloggers.id')
        ->select('stories.*', 'bloggers.first_name', 'bloggers.last_name')
        ->orderBy('id', 'DESC')
        ->paginate(6);
        return view('stories', compact('stories')); 
    }

    function story($id){
         $story = Stories::join('bloggers', 'stories.blogger_id', 'bloggers.id')
        ->where('stories.id', $id)
        ->select('stories.*', 'bloggers.first_name', 'bloggers.last_name')
        ->first();
        return view('story', compact('story'));  
    }

    function project($id){
        $project = Projects::join('causes', 'projects.cause_id', 'causes.id')
        ->select('projects.*', 'causes.cause')
        ->where('projects.id', $id)
        ->first();
        return view('project', compact('project'));  
    }
    
    function causes(){
        $causes = Causes::all();
        return view('causes', compact('causes')); 
    }

    function application_sent(){
        return Inertia::render('ApplicationSent');  
    }

    function events(){
        $upcomingEvents = Events::where('date', '>=', date('Y-m-d'))->get();
        $passedEvents = Events::where('date', '<', date('Y-m-d'))->get();
        return view('events', compact('upcomingEvents', 'passedEvents'));  
    }

    function event($event){
        $event = Events::where('event', $event)->first();
        return view('event', compact('event'));  
    }
    
}
