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
                           
    
@endsection


@section('inline-scripts')
@endsection
