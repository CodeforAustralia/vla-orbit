@extends ('layout.master')


@section ('content')

    <div class="col-sm-8 blog-main">
        <h1>{{$post->title}}</h1>
        
        <p>{{ $post->body }}</p>
        
        <div class="comments">
            
            <ul class="list-group">
                @foreach( $post->comments as $comment )
                <li class="list-group-item">
                    
                    <strong>{{ $comment->created_at->diffForHumans() }}</strong> &nbsp;
                    
                    {{ $comment->body }}
                </li>
                @endforeach
            </ul>
            
        </div>
        
        {{-- Add a comment --}}
        
        <hr>
        
        <div class="card">
            
            <div class="card-block" >
                
                <form action="/posts/{{ $post->id }}/comments/" method="POST">
                    
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <textarea name="body" placeholder="your comment here." class="form-control" ></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"> Add Comment</button>
                    </div>
                    
                </form>
                
                @include ('orbit.errors')
                
            </div>
            
        </div>
        
        
    </div>

@endsection