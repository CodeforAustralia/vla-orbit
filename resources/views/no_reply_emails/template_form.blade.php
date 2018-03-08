    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-envelope"></i>No-Reply Email</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/no_reply_emails/templates" class="form-horizontal nre_template_form" id="nre_template_form">
                {{ csrf_field() }}
                <div class="form-body">

                    <div class="form-group">
                        <div class="col-md-4">
                            <input type="text" class="form-control hidden" id="RefNo" name="RefNo" value="{{ ( isset($template['RefNo']) ? $template['RefNo'] : 0 ) }}">
                        </div>
                    </div>

                    @if( \App\Http\helpers::getRole() === 'Administrator' )
                    <div class="form-group">
                        <label class="col-md-3 control-label">Service Provider:</label>
                        <div class="col-md-8 col-lg-4">
                            <select class="form-control" id="Section" name="Section"> 
                                <option></option>
                                @foreach( $service_providers as $service_provider )
                                <option value="{{ $service_provider['ServiceProviderName'] }}" {{ ( isset($template['Section']) && (strpos($service_provider['ServiceProviderName'], $template['Section']) !== false) ? 'selected' : '') }}> {{ $service_provider['ServiceProviderName'] }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                    <div class="form-group">
                        <div class="ccol-md-8 col-lg-4">
                            <input type="text" class="form-control hidden" id="Section" name="Section" value="{{ ( isset($template['Section']) ? $template['Section'] : '' ) }}">
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="col-md-3 control-label">Template name:</label>
                        <div class="col-md-8 col-lg-4">
                            <input type="text" class="form-control" id="name" name="name" value="{{ ( isset($template['Name']) ? $template['Name'] : '' ) }}" maxlength="255" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Subject:</label>
                        <div class="col-md-8 col-lg-4">
                            <input type="text" class="form-control" id="subject" name="subject" value="{{ ( isset($template['Subject']) ? $template['Subject'] : '' ) }}" maxlength="255" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Make template available for everyone?:</label>
                        <div class="col-md-8 col-lg-4">
                            <input type="checkbox" class="checkbox_all" id="all" name="all" {{ ( isset($template['Section']) && $template['Section'] == 'All' ? 'checked' : '' ) }}>
                        </div>
                    </div>
                    

                    <div class="form-group">
                        <label class="col-md-3 control-label">Template:</label>
                        <div class="col-md-7">                            
                            <textarea class="form-control" rows="15" class="form-control" id="template" name="template">{{ ( isset($template['TemplateText']) ? $template['TemplateText'] : '' ) }}</textarea>
                        </div>
                    </div>
        
                </div>

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green">Save</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>


@section('styles')    

@endsection

@section('scripts')    
    <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="/js/no-reply-email-template.js" type="text/javascript"></script>
@endsection