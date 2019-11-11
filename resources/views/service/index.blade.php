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
            <div id="dTables" class="service_data_table">
                <data-table
                    fetch-url="/service/list_table"
                    show-url="#"
                    show-label="Send To Client"
                    edit-url="/service/show"
                    delete-url="/service/delete"
                    title="Service"
                    per-page="20"
                    model="service"
                    identifier="sv_id"
                    :columns="[
                                'sv_id',
                                'name',
                                'service_provider',
                                'phone',
                                'email',
                                'service_type',
                                'service_level',
                                'updated_on'
                            ]"
                    description="Below is a list of all services in LHO - use the search box to the right to locate specific services. If the service is available you can use the blue 'send to client' button to send the service details to a client by SMS or email. If you have administrator access to a service you can use the yellow 'edit' button to update the service details."
                ></data-table>
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
                    <h4 class="modal-title bold col-xs-10" id="serviceName" v-html="title"></h4>

                    <div class="col-xs-2" id="toggle_status" v-show="!send_to_client">
                        <toggle-button
                            v-model="status"
                            class="pull-right"
                            :labels="{checked: 'Yes', unchecked: 'No'}"
                            :sync="true"
                            :color="{checked:'#32c5d2', unchecked:'#e73d4a'}"
                            disabled/>
                    </div>

                </div>
                <!-- Modal Body -->
                <div class="modal-body padding-top-10">
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
    <script src="{{ asset('js/tables.js') }}?id={{ str_random(6) }}"></script>
@endsection