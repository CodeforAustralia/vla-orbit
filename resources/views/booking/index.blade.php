@extends ('orbit.master')

@section ('content')
    
    @include ('orbit.alert')
    
    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Bookings </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/booking/new">New Booking</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Demo Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-container">
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_bookings">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="5%" > Id </th>
                            <th width="10%"> Service Name </th>
                            <th width="5%" > Date </th>
                            <th width="5%" > Time </th>
                            <th width="10%"> First Name </th>
                            <th width="10%"> Last Name </th>
                            <th width="10%"> Email </th>
                            <th width="10%"> Phone </th>
                            <th width="10%"> </th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Demo Datatable services -->

    <!-- Content -->
    <div class="row"> 
      <div class="col-xs-12">
        <div class="portlet light">
          <div class="portlet-title">
            <div class="caption">
              <span class="caption-subject font-green sbold"><i class="icon-list font-green"></i>&nbsp;  Recieved Bookings</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-xs-12">
                <p>In this data table we need the following columns:</p>
                <ul>
                    <li>ID</li>
                    <li>Service Name</li>
                    <li>Date</li>
                    <li>Time</li>
                    <li>First Name</li>
                    <li>Last Name</li>
                    <li>Email</li>
                    <li>Phone</li>
                    <li>Safe to Contact? (In this column we will have two icons, one for phone and one for email. If 'safe to contact' is selected for either turn the icon to green-jungle and if not set to greyed out)</li>
                    <li>Requirements (In this column we will have a disability icon that turns blue if the user has disability requirements and a translator icon that turns blue if a translator is required)</li>
                    <li>Actions (Buttons: Edit, Delete)</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Content -->
    <div class="row"> 
      <div class="col-xs-12">
        <div class="portlet light">
          <div class="portlet-title">
            <div class="caption">
              <span class="caption-subject font-green sbold"><i class="icon-list font-green"></i>&nbsp;  Sent Bookings</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-xs-12">
                <p>In this data table we need the following columns:</p>
                <ul>
                    <li>ID</li>
                    <li>Service Name</li>
                    <li>Date</li>
                    <li>Time</li>
                    <li>First Name</li>
                    <li>Last Name</li>
                    <li>Email</li>
                    <li>Phone</li>
                    <li>Safe to Contact? (In this column we will have two icons, one for phone and one for email. If 'safe to contact' is selected for either turn the icon to green-jungle and if not set to greyed out)</li>
                    <li>Requirements (In this column we will have a disability icon that turns blue if the user has disability requirements and a translator icon that turns blue if a translator is required)</li>
                    <li>Actions (Buttons: Edit, Delete)</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
                
@endsection
