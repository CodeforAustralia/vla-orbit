
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        @include ('orbit.errors')

        <form method="POST" action="/dashboard">
          
            {{ csrf_field() }}
            <div class="form-body">
                <input type="text" class="form-control hidden" id="id" name="id" value="{{ $dashboard->id or '' }}">

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $dashboard->title or '' }}" required>
                </div>
                
                <div class="form-group">
                    <label for="body">Body:</label>
                    <textarea class="form-control" id="body" name="body" required>
                      {{ $dashboard->body or '' }}
                    </textarea>
                </div>
                          
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-lg green">Save Message</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- END FORM-->
    </div>

@section('inline-scripts')

$('#body').summernote({
  toolbar: [
      // [groupName, [list of button]]
      ['style', ['bold', 'italic', 'underline', 'color']],
      ['fontsize', ['fontname','fontsize']],
      ['para', ['ul', 'ol', 'paragraph']],          
      ['link', ['linkDialogShow', 'unlink']],
      ['height', ['height']]              
  ],
      height: 500
}); 

@endsection