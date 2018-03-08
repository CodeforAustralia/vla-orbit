    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-envelope"></i>No-Reply Email
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/no_reply_emails" class="form-horizontal" id="nre_form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-body">
                    
                    <div class="form-group ">
                        <label class="col-md-3 control-label">Template:</label>
                        <div class="select2-bootstrap col-md-8 col-lg-4">
                            <select class="form-control select2" id="template_id" name="template_id" required>
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">To:</label>
                        <div class="col-md-8 col-lg-4">
                            <input type="email" class="form-control" id="to" name="to"  value="" placeholder="Client email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Subject:</label>
                        <div class="col-md-8 col-lg-4">
                            <input type="text" class="form-control" id="subject" name="subject"  value="" placeholder="Subject of the message" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Message:</label>
                        <div class="col-md-7">                            
                            <textarea class="form-control" rows="15" class="form-control" id="message" name="message" placeholder="Client requirements, special needs, difficulties experienced with client, time limits, instructions for contact or any other information that may be useful for the service provider to know beforehand."  required></textarea>
                        </div>
                    </div>
        
                </div>

                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-7">
                            
                        <div class="form-group mt-repeater">
                            <div data-repeater-list="attachments">
                                <div class="mt-repeater-item">
                                    <label class="control-label">Attachment</label>
                                    <input type="file" name="main_attachment" class="form-control"> 
                                </div>
                                    <div data-repeater-item class="mt-repeater-item mt-overflow">
                                        <label class="control-label">Additional Attachment</label>
                                        <div class="mt-repeater-cell">
                                            <input type="file" name="files" class="form-control mt-repeater-input-inline" />
                                            <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete mt-repeater-del-right mt-repeater-btn-inline">
                                                <i class="fa fa-close"></i>
                                            </a>
                                        </div>
                                    </div>
                            </div>
                            <a href="javascript:;" data-repeater-create class="btn btn-success mt-repeater-add">
                                <i class="fa fa-plus"></i> Add new Attachment</a>
                        </div>
                    </div>
                </div>
                

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green">Send</button>
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
    <script src="/js/no-reply-emails.js" type="text/javascript"></script>    
    <script src="/assets/global/plugins/jquery-repeater/jquery.repeater.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/form-repeater.min.js" type="text/javascript"></script>
@endsection