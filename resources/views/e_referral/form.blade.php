
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