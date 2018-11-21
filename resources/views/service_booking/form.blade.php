    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-book"></i>{{ isset($current_service_booking) ? 'Edit ' : 'New ' }}Service Booking
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
                        <label class="col-md-3 control-label" for="bbsv_id">Booking Service Name:</label>
                        <div class="select2-bootstrap col-sm-4 col-md-4">
                            <select id="bbsv_id" class="form-control select2" name="BookingServiceId" placeholder="Select a service" required>
                            <option> </option>
                                @foreach( $booking_services as $booking_service)
                                    <option value="{{ $booking_service['id'] }}"> {{ $booking_service['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden"  id="bbisv_id" name="InternalBookingServId"  value="0" >

                    <input type="hidden"  id="bbr_id" name="ResourceId"  value="0" >

                    <input type="hidden"  id="lenght_app" name="ServiceLength"  value="0" >

                    <input type="hidden"  id="lenght_int_app" name="IntServiceLength"  value="0">
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
    $('#bbsv_id').select2({placeholder: "-- Select from list --"}).val( {{ isset($current_service_booking) ? $current_service_booking->BookingServiceId : 'null' }} ).trigger("change");
  });
@endsection