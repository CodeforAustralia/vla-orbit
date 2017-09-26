
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>{{ (isset($current_sms_template) ? 'Edit' : 'New') }} SMS Template</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/sms_template" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">

                    <div class="form-group hidden">
                        <input type="text" class="form-control" id="st_id" name="st_id" value="{{ isset($current_sms_template) ? $current_sms_template['TemplateId'] : 0 }}" required>
                    </div>

                    <div class="form-group">
                        <label for="single" class="col-md-3 control-label">Sevice Name:</label>
                        <div class="select2-bootstrap col-sm-4 col-md-7">
                            <select id="single" class="form-control select2" name="sv_id">
                                <option> </option>
                                @foreach( $services as $service)
                                    <option value="{{ $service['ServiceId'] }}"> {{ $service['ServiceName'] }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Template:</label>
                        <div class="col-sm-4 col-md-7">
                            <textarea rows="5" class="form-control" id="template" name="template" required>{{ isset($current_sms_template) ? $current_sms_template['Template'] : '' }}</textarea>

                            <div class="char-box"><span>Characters remaining: </span><span id="char-count"></span></div>
                            <span class="help-block">Tags:</span>  
                            <button type="button" class="btn btn-primary btn-xs shortcut-tag tooltips" data-toggle="tooltip" title="Date of appointment">(date)</button>&nbsp;
                            <button type="button" class="btn btn-primary btn-xs shortcut-tag tooltips" data-toggle="tooltip" title="Time of appointment">(time)</button>&nbsp;
                            <button type="button" class="btn btn-primary btn-xs shortcut-tag tooltips" data-toggle="tooltip" title="Location of appointment">(location)</button>&nbsp;
                            <button type="button" class="btn btn-primary btn-xs shortcut-tag tooltips" data-toggle="tooltip" title="Office number">(phone)</button>&nbsp;                            
                            <button type="button" class="btn btn-primary btn-xs shortcut-tag tooltips" data-toggle="tooltip" title="Client's name">(client_name)</button>&nbsp;
                            <button type="button" class="btn btn-primary btn-xs shortcut-tag tooltips" data-toggle="tooltip" title="Show service name">(service_name)</button>

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


@section('scripts')
  <script src="/js/sms_template.js?id={{ str_random(6) }}"></script>
@endsection

@section('inline-scripts')
  $(document).ready(function() {
    $('#single').select2({placeholder: ""}).val( {{ isset($current_sms_template) ? $current_sms_template['ServieId'] : 'null' }} ).trigger("change");
  });
@endsection