@extends ('orbit.master')

@section ('content')
    <!-- BEGIN PAGE HEADER-->
    <h1 class="page-title"> Create Matter </h1>
    <!-- END PAGE HEADER-->
    
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>New Legal Matter</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form action="/matter" method="POST" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Name</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Enter text" id="title" name="title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tag</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Enter text" id="tag" name="tag">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Description</label>
                        <div class="col-md-4">
                            <textarea class="form-control" rows="3" id="description" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Parent</label>
                        <div class="col-md-4">
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option>Option 1</option>
                                <option>Option 2</option>
                                <option>Option 3</option>
                                <option>Option 4</option>
                                <option>Option 5</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Legal Matter Type</label>
                        <div class="col-md-4">
                            <select class="form-control" id="lmt_id" name="lmt_id">
                            @foreach($matter_types as $matter_type)
                                <option value="{{ $matter_type['MatterTypeID'] }}">{{ $matter_type['MatterTypeName'] }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green">Submit</button>
                            <button type="button" class="btn btn-circle grey-salsa btn-outline">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
@endsection