@extends ('orbit.master')

@section ('content')

    @include ('orbit.alert')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Eligibility Criteria </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/question/new/2">New Question</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->
    
    <!-- Begin: Demo Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-container">
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_question_eligibility">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="5%"> Id </th>
                            <th width="50%"> Name </th>
                            <th width="5%"> </th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Demo Datatable services -->
@endsection
