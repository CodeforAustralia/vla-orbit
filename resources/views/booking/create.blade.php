@extends ('orbit.master')

@section ('content')
<!-- Questions -->
<div class="row">
    <div class="col-xs-10 col-lg-10 col-xs-offset-1 col-lg-offset-1">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-green sbold"><i class="icon-question font-green"></i>&nbsp; New Booking / Intake</span>
                </div>
            </div>
            <div class="portlet-body form">
                @include("booking.form")
            </div>
        </div>
    </div>
</div>
   
<!-- Bottom Padding -->
<div class="row">
    <div class="col-xs-12">
        <br>
        <br>
    </div>
</div>
    <!-- END PAGE HEADER-->        
                                    
<!-- Confirm Eligibility Modal -->                                        
    <div class="modal fade in" id="EligibilityConfirm" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-hidden="false" role="dialog" aria-labelledby="EligibilityConfirm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title" id="SelectMatchLabel">Confirm Service Eligibility</h4>
                </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <p>Service Guidelines Not Available</p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6">
                    <button type="button" data-dismiss="modal" class="btn btn-block btn-outline dark">Cancel</button>
                </div>
                <div class="col-sm-6">
                    <button type="button" data-dismiss="modal" class="btn btn-block disabled green-jungle">Confirm Client Eligibility</button>
                </div>
        </div>
    </div>
    
<!-- Confirm Eligibility Modal -->                                        
    <div class="modal fade in" id="EligibilityConfirm" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-hidden="false" role="dialog" aria-labelledby="EligibilityConfirm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title" id="SelectMatchLabel">Confirm Service Eligibility</h4>
                </div>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <p>Details, location and eligibility </p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6">
                    <button type="button" data-dismiss="modal" class="btn btn-block btn-outline dark">Cancel</button>
                </div>
                <div class="col-sm-6">
                    <button type="button" data-dismiss="modal" class="btn btn-block green-jungle">Confirm Client Eligibility</button>
                </div>
            </div>
        </div>
    </div>    

@endsection


@section('inline-scripts')
@endsection
