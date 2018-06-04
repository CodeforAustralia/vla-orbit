<?php

namespace App\Http\Controllers;

use App\Post;

/**
 * Post Controller.
 *   
 * @author VLA & Code for Australia
 * @version 1.2.0
 * @see  Controller
 */
class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }
    
    public function index()
    {
        $posts = Post::latest()->get();
        return view("posts.index", compact('posts'));
    }

    public function show(Post $post)
    {
        return view("posts.show", compact('post'));
    }
    
    public function create()
    {
        return view("posts.create");
    }
    
    public function store()
    {
        $this->validate(request(),[
            'title' => 'required',
            'body' => 'required'
        ]);
        /*
        Post::create([
            'title' => request('title'),
            'body' => request('body'), 
            'user_id' => auth()->id()
            ]);
        */
        auth()->user()->publish(
            
            new Post(request(['title', 'body']))
                
        );    
            
        return redirect('/posts');
    }
}
