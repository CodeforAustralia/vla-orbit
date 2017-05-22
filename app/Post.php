<?php

namespace App;


class Post extends Model
{
    protected $table = 'post';
    
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function addComment($body)
    {
        
        //$this->comments()->create(compact('body'));
        
        //add a coment to a post
        
        Comment::create([
            'body'      => $body,
            'post_id'   => $this->id,
            'user_id'   => auth()->id()
        ]);
    }
}
