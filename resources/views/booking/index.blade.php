@extends ('orbit.master')

@section ('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Bookings </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/booking/new">New Booking</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->
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
