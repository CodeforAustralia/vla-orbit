<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;

/**
 * Comment Controller.
 * Controller for store the comments
 *  * 
 * @author VLA & Code for Australia
 * @version 1.2.0
 */
class CommentsController extends Controller
{
	/**
	 * Store a newly or updated comment in the data base
	 * @param  Post   $post comment to post
	 * @return mixed  previous page
	 */
    public function store(Post $post)
    {
        $this->validate(request(), ['body' => 'required|min:2']);
        
        $post->addComment(request('body'));
        
        return back();
    }
}
