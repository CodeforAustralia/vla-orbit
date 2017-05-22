
    <div class="blog-masthead">
      <div class="container">
        <nav class="blog-nav">
          <a class="blog-nav-item active" href="#">Home</a>
          <a class="blog-nav-item" href="#">New features</a>
          <a class="blog-nav-item" href="#">Press</a>
          <a class="blog-nav-item" href="#">New hires</a>
          
          @if(Auth::check())
            <a class="blog-nav-item pull-right" href="#">{{ Auth::user()->name }}</a>
          @endif
        </nav>
      </div>
    </div>