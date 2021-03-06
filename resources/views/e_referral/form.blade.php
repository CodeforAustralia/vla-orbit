
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>New E-Referral Form</div>
    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form method="POST" action="/e_referral" class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-body">

                <div class="form-group">
                    <label class="col-md-3 control-label">Name</label>
                    <div class="col-md-4">
                    <input type="hidden" class="form-control" placeholder="Enter text" id="RefNo" name="RefNo" value="{{ ( isset($e_referral) ? $e_referral->RefNo : 0 )}}">
                        <input type="text" class="form-control" placeholder="Enter text" id="Name" name="Name" value="{{ ( isset($e_referral) ? $e_referral->Name : '' )}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Description:</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="Description" name="Description" value="{{ ( isset($e_referral) ? $e_referral->Description : '' )}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Header:</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="Header" name="Header" value="{{ ( isset($e_referral) ? $e_referral->Header : '' )}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Display fields in form:</label>
                    <div class="col-sm-3">
                        <label class="checkbox-inline">
                            <input type="checkbox" value="" id="email" name="dob" {{ ( isset( $fields )  && in_array('dob', $fields) ? 'checked' : '' ) }}>
                            Date of Birth
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-3">
                        <label class="checkbox-inline">
                            <input type="checkbox" value="" id="suburb" name="suburb" {{ ( isset( $fields )  && in_array('suburb', $fields) ? 'checked' : '' ) }}>
                            Suburb
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-3">
                        <label class="checkbox-inline">
                            <input type="checkbox" value="" id="postal_address" name="postal_address" {{ ( isset( $fields )  && in_array('postal_address', $fields) ? 'checked' : '' ) }}>
                            Postal Address
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-3">
                        <label class="checkbox-inline">
                            <input type="checkbox" value="" id="email" name="email" {{ ( isset( $fields )  && in_array('email', $fields) ? 'checked' : '' ) }}>
                            Email
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-3">
                        <label class="checkbox-inline">
                            <input type="checkbox" value="" id="court_date" name="court_date" {{ ( isset( $fields )  && in_array('court_date', $fields) ? 'checked' : '' ) }}>
                            Court Date
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Body of booking:</label>
                    <div class="col-md-8">
                        <textarea class="form-control hidden" id="body_text" rows="20">{{ ( isset($e_referral) && isset( $e_referral->Body ) && $e_referral->Body  ? $e_referral->Body : '' )}}</textarea>
                        <div id="editor" class="editor">
                            <vue-mce
                                id="Body"
                                name="Body"
                                class="form-control"
                                :value="text_in_editor"
                                :config="config"/>
                        </div>
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

@section('scripts')
<script src="https://cloud.tinymce.com/dev/tinymce.min.js?apiKey={{ env('TYTINYMCE_KEY') }}" ></script>
<script src="/js/e_referral.js" type="text/javascript"></script>
@endsection