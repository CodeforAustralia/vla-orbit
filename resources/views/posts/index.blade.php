@extends ('layout.master')

@section ('content')
  
        <div class="col-sm-8 blog-main">

          @foreach ($posts as $post)
            @include ('posts.post')
          @endforeach

          <nav>
            <ul class="pager">
              <li><a href="#">Previous</a></li>
              <li><a href="#">Next</a></li>
            </ul>
          </nav>

        </div><!-- /.blog-main -->
@endsection