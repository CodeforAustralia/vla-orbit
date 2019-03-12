@extends ('orbit.master')

@section ('content')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Services </h1>
        @if ( in_array( \App\Http\helpers::getRole(), ['Administrator', 'AdminSp' , 'AdminSpClc']) )
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/service/new">New Service</a>
        @endif
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-container">
                <div class="table-actions-wrapper">
                    <div id="service_filter" class="dataTables_filter"><label>Search: <input type="search" id="search_box" class="" placeholder="" aria-controls="service"></label></div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_service">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="5%"> Id </th>
                            <th width="15%"> Name </th>
                            <th width="15%"> Service Provider </th>
                            <th width="10%"> Phone </th>
                            <th width="5%"> Email </th>
                            <th width="10%"> Service type </th>
                            <th width="5%"> Service Level </th>
                            <th width="10%"> Actions</th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Datatable services -->

  <!-- Modal Start -->
    <div class="modal fade" id="viewService" tabindex="-1" role="dialog" aria-labelledby="viewService">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" @click="clearFields" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title bold" id="serviceName" v-html="title"></h4>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="container-fluid">
                    <!-- Top -->
                        @include('service.view_service_modal')
                    </div> <!-- Modal Body Close-->
                </div><!-- Modal Content Close-->
            </div><!-- Modal Dialogue Close-->
        </div><!-- Modal Fade Close-->
    </div>
@endsection
@section('styles')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <!-- Bootstrap toogle CSS -->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<!-- END Bootstrap toogle CSS -->
@endsection
@section('scripts')
    <script src="/js/view_service_vue.js?id={{ str_random(6) }}"></script>
    <!-- Bootstrap toogle JS -->
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <!-- END Bootstrap toogle JS -->
@endsection