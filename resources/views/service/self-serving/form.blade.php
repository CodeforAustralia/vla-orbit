<div class="service-container">

    <div class="portlet box yellow">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>Service
            </div>
        </div>
        <div class="portlet-body" style="display: block;">
            <div class="tabbable-line">
                <ul class="nav nav-tabs ">
                    <li class="active">
                        <a href="#service_general_settings" data-toggle="tab"> General Settings </a>
                    </li>
                    <li>
                        <a href="#service_clients_matters" data-toggle="tab"> Client / Matters </a>
                    </li>
                    <li>
                        <a href="#service_legal_matters" data-toggle="tab"> Legal Matters </a>
                    </li>
                    <li>
                        <a href="#service_intake_options" data-toggle="tab"> Intake Options </a>
                    </li>
                </ul>
                <div class="tab-content">

                    <div class="tab-pane active" id="service_general_settings">
                        <service-general-settings></service-general-settings>
                    </div>

                    <div class="tab-pane" id="service_clients_matters">
                        <service-clients-matters></service-clients-matters>
                    </div>

                    <div class="tab-pane" id="service_legal_matters">
                        <service-legal-matters></service-legal-matters>
                    </div>
                    <div class="tab-pane" id="service_intake_options">
                        <service-intake-options></service-intake-options>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


@section('scripts')
    <script src="/js/service_management.js"></script>
@endsection