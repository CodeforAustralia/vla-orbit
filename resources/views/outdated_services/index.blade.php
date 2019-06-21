@extends ('orbit.master')

@section ('content')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> Outdated Services </h1>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable outdated_services">
        <div class="portlet-body">
            <table>
                <div class="table-responsive">
                    <table-local></table-local>
                </div>
            </table>
        </div>
    </div>
    <!-- End: Datatable services -->

{{-- 
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
    </div> --}}
@endsection
@section('styles')
@endsection
@section('scripts')
    <script src="{{ asset('js/outdated_services.js') }}?id={{ str_random(6) }}"></script>
@endsection