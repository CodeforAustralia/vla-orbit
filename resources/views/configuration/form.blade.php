@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-briefcase"></i>Configuration</div>
    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form method="POST" action="/configuration" class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-body">

                <div class="form-group margin-bottom-5">
                    <label for="about" class="col-md-3 control-label">
                        Key:
                    </label>

                    <div class="col-md-4">
                        @if(isset($current_config))
                            <span class="text-inline"> {{ $current_config['Key'] }} </span>
                            <input class="form-control hidden" rows="5" class="form-control" id="Key" name="Key" value="{{$current_config['Key']}}" required>
                        @else
                            <input class="form-control" rows="5" class="form-control" id="Key" name="Key" value="" required>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">
                        Name:
                    </label>

                    <div class="col-md-4">
                        <input type="text" class="form-control" id="Name" name="Name" value="{{ isset($current_config) ? $current_config['Name'] : '' }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="url" class="col-md-3 control-label">
                        Value:
                    </label>

                    <div class="col-md-4">
                        <input type="text" class="form-control" id="Value" name="Value" value="{{ isset($current_config) ? $current_config['Value'] : '' }}" required>
                    </div>
                </div>

            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green">Submit</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- END FORM-->
    </div>
</div>