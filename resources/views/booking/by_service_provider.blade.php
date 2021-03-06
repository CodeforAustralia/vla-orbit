@extends ('orbit.master')

@section ('content')

    <div id="sp-calendar">
        <!-- BEGIN PAGE HEADER-->
        <div class="portlet">
            <h1 class="page-title col-xs-10"> Bookings by Service Provider</h1>
            <br>
        </div>
        <!-- END PAGE HEADER-->

        <div class="row">
            <div class="col-md-12">
                <div class="portlet light portlet-fit">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-layers font-green"></i>
                            <span class="caption-subject font-green sbold uppercase">Select Service Provider</span>
                        </div>
                    </div>
                    <div class="portlet-body">

                        <div class="row">
                            <div class="form-group">
                                <multiselect
                                v-model="service_provider_selected"
                                label="text"
                                key="id"
                                id="service-provider-select"
                                track-by="text"
                                placeholder="Select Service..."
                                open-direction="bottom"
                                :options="service_provider_options"
                                :multiple="false"
                                :searchable="true"
                                :close-on-select="true"
                                :show-no-results="false"
                                :show-labels="false"
                                @input="getServiceProviderBookings"
                                >
                                </multiselect>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Start -->

        <div class="row">
            <div class="col-md-12">
                <div class="portlet light portlet-fit calendar">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-layers font-green"></i>
                            <span class="caption-subject font-green sbold uppercase">Office Bookings</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <Calendar
                                :service_provider_id="service_provider_id"
                                :current_booking="current_booking"
                                :booking_to_delete="booking_to_delete"
                                :booking_status_options = "booking_status_options"
                                :booking_to_update = "booking_to_update"
                                v-on:update:reset_booking_to_delete="booking_to_delete = 0"
                                v-on:update:current_booking="current_booking = $event"
                                v-on:update:booking_status_options="booking_status_options = $event"
                                v-on:update:booking_to_update="booking_to_update = 0">
                                </Calendar>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Calendar End -->

        <!-- Modal Start -->
        @include("booking.booking_info_modal_vue")
        <!-- Modal Fade Close-->
        <div class="row vue-tables" id="datatable">
            <user_booking_datatable></user_booking_datatable>
        </div>
    </div>

@endsection

@section('styles')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <link href="/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
@endsection

@section('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="https://cloud.tinymce.com/dev/tinymce.min.js?apiKey=v3tjlgkjdlr8xiq21qsdopbjfkuk5ibmdhgb5yznjfpyb1lj" ></script>
    <script src="/js/calendar_vue.js?id={{ str_random(6) }}" type="text/javascript"></script>
    <script src="/js/edit-booking.js?id={{ str_random(6) }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

@endsection

@section('inline-scripts')
@endsection