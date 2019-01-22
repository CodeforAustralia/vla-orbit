@extends ('orbit.master')

@section ('content')

    <!-- Begin: Demo Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-title">
            <div class="caption">
            <span class="caption-subject font-green sbold uppercase"><i class="icon-list font-green"></i>All Bookings</span>
            </div>
        </div>
        <div class="portlet-body">
            <div id="datatable" class="vue-tables">
                <div class="row">
                    <div class="col-xs-12 form-inline margin-bottom-20">
                        <div class="form-group pull-right">
                            <label>
                                Search:
                                <input type="text" class="form-control" v-model="filter" placeholder="Search">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="table" class="col-xs-12 table-responsive">
                        <datatable :columns="columns" :data="rows" :filter-by="filter"></datatable>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End: Demo Datatable services -->

@endsection

@section('scripts')
    <!-- BEGIN PAGE LEVEL SCRIPTS -->

    <script src="/js/remind-booking.js?id={{ str_random(6) }}" type="text/javascript"></script>
    <script src="/js/datatable/future_bookings_datatable.js?id={{ str_random(6) }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

@endsection