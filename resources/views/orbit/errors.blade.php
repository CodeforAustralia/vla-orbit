@if(count($errors))

    <div class="form-group">

        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)

            @if($error == 'The password format is invalid.')
            <li>The password must contain at least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character (#?!@$%^&*-+).</li>
            @else
            <li>{{ $error }}</li>
            @endif

            @endforeach
          </ul>

        </div>

    </div>

@endif