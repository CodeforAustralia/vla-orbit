    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>{{ isset($current_service_booking) ? 'Edit ' : 'New ' }}Service Booking
            </div>
        </div>
        <div class="portlet-body form">           
            <!-- BEGIN FORM-->
            @include ('orbit.errors')
            <form method="POST" action="/service_booking" class="form-horizontal" >
                {{ csrf_field() }}
                <div class="form-body">
                    <div class="form-group hidden">
                        <input type="text" class="form-control" id="sb_id" name="RefNo" value="{{ isset($current_service_booking) ? $current_service_booking->RefNo : 0 }}" required>
                    </div>

                    <div class="form-group">
                        <label for="single" class="col-md-3 control-label">Sevice Name:</label>
                        <div class="select2-bootstrap col-sm-4 col-md-4">
                            <select id="service_list" class="form-control select2" name="ServiceId" required>
                             <option> </option>                            
                                @foreach( $services as $service)
                                    <option value="{{ $service['ServiceId'] }}"> {{ $service['ServiceName'] }} - [ {{ $service['ServiceProviderName'] }} ]</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="bbsv_id">BB Service Id:</label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" id="bbsv_id" name="BookingServiceId"  value="{{ isset($current_service_booking) ? $current_service_booking->BookingServiceId : '' }}" placeholder="" required>
                        </div>
                    </div>                               
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="bbisv_id">BB Interpreter Id:</label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" id="bbisv_id" name="InternalBookingServId"  value="{{ isset($current_service_booking) ? $current_service_booking->InternalBookingServId : '' }}" placeholder="">
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="bbr_id">BB Resource Id:</label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" id="bbr_id" name="ResourceId"  value="{{ isset($current_service_booking) ? $current_service_booking->ResourceId : '' }}" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="lenght_app">Length of Appt:</label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" id="lenght_app" name="ServiceLength"  value="{{ isset($current_service_booking) ? $current_service_booking->ServiceLength : '' }}" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="lenght_int_app">Length of Int Appt:</label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" id="lenght_int_app" name="IntServiceLength"  value="{{ isset($current_service_booking) ? $current_service_booking->IntServiceLength : '' }}" placeholder="">
                        </div>
                </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green">Save</button>
                            <button type="button" onclick="window.location='/service_booking';return false" class="btn btn-circle grey-salsa btn-outline">Cancel</button>
                        </div>
                    </div>
                </div>                               
            </form>
            <!-- END FORM-->
        </div>
    </div>


@section('styles')    

@endsection

@section('inline-scripts')
  $(document).ready(function() {
    $('#service_list').select2({placeholder: "-- Select from list --"}).val( {{ isset($current_service_booking) ? $current_service_booking->ServiceId : 'null' }} ).trigger("change");
  });  
@endsection