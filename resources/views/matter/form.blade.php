
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

                    <div class="form-group hidden">
                        <input type="text" class="form-control" id="MatterID" name="MatterID" value="{{ isset($current_mattter) ? $current_mattter->MatterID : 0 }}" required>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Name</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Enter text" id="title" name="title" value="{{ isset($current_mattter) ? $current_mattter->MatterName : '' }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Tag</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Enter text" id="tag" name="tag" value="{{ isset($current_mattter) ? $current_mattter->Tag : '' }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Description</label>
                        <div class="col-md-4">
                            <textarea class="form-control" rows="3" id="description" name="description" required>{{ isset($current_mattter) ? $current_mattter->Description : '' }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Parent</label>
                        <div class="col-md-4">
                            <select class="form-control" id="parent_id" name="parent_id">                                
                                <option value="50"></option>
                                @foreach($matters as $matter)
                                    @if( $matter['MatterID'] != 50 )
                                        <option value="{{ $matter['MatterID'] }}" {{ (isset($current_mattter) && $matter['MatterID'] ==  $current_mattter->ParentId ) ? 'selected' : '' }} >{{ $matter['MatterName'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Legal Matter Type</label>
                        <div class="col-md-4">
                            <select class="form-control" id="lmt_id" name="lmt_id">
                            @foreach($matter_types as $matter_type)
                                <option value="{{ $matter_type['MatterTypeID'] }}" {{ (isset($current_mattter) && $matter_type['MatterTypeID'] ==  $current_mattter->TypeId ) ? 'selected' : '' }} >{{ $matter_type['MatterTypeName'] }}</option>
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