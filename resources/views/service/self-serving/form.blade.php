<div class="service-container" id='service_self_serving'>

    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>Service
            </div>
        </div>
        <div class="portlet-body">
            <div class="tabbable-line padding-top-0">
                <ul class="nav nav-tabs ">
                    <li class="active">
                        <a @click="change_tab('settings')" href="#service_general_settings" data-toggle="tab"> General Settings </a>
                    </li>
                    <li>
                        <a v-if="!is_new_service" @click="change_tab('eligibility')" href="#service_clients_matters" data-toggle="tab"> Eligibility Criteria </a>
                        <a v-else @click="save_service_first()"> Eligibility Criteria </a>
                    </li>
                    <li>
                        <a v-if="!is_new_service" @click="change_tab('matters')" href="#service_legal_matters" data-toggle="tab"> Legal Matters </a>
                        <a v-else @click="save_service_first()"> Legal Matters</a>
                    </li>
                    <li>
                        <a v-if="!is_new_service" @click="change_tab('intake_options')" href="#service_intake_options" data-toggle="tab"> Intake Options </a>
                        <a v-else @click="save_service_first()"> Intake Options</a>
                    </li>
                    <li>
                        <a v-if="!is_new_service" href="#service_client_history" data-toggle="tab"> Service History </a>
                    </li>
                </ul>
                <div class="tab-content padding-top-10">

                    <div class="tab-pane active" id="service_general_settings">
                        <service-general-settings
                            :service_providers ='{{ json_encode($service_providers) }}'
                            :service_types = '{{ json_encode($service_types) }}'
                            :service_levels = '{{ json_encode($service_levels) }}'
                            @isset($current_service)
                                :current_service = '{{ json_encode($current_service) }}'
                            @endisset
                            @isset($catchments)
                                :catchments = '{{ json_encode($catchments) }}'
                            @endisset
                            @isset($service_notes_log)
                                :service_notes_log = '{{ json_encode($service_notes_log) }}'
                            @endisset>
                        </service-general-settings>
                    </div>

                    <div class="tab-pane" id="service_clients_matters">
                        <service-clients-matters
                            :eligibility_questions='{{  htmlspecialchars(json_encode($vulnertability_questions), ENT_QUOTES, 'UTF-8')  }}'
                            @isset($current_vulnerabilities)
                                :selected_eligibility_questions='{!! json_encode($current_vulnerabilities) !!}'
                            @endisset
                            @isset($current_service)
                                :current_service='{{ json_encode($current_service) }}'
                            @endisset
                            ></service-clients-matters>
                    </div>

                    <div class="tab-pane" id="service_legal_matters">
                        <service-legal-matters
                            @isset($current_service)
                                :current_service = '{{ json_encode($current_service) }}'
                            @endisset
                                :eligibility_questions='{{  htmlspecialchars(json_encode($vulnertability_questions), ENT_QUOTES, 'UTF-8')  }}'
                        ></service-legal-matters>
                    </div>

                    <div class="tab-pane" id="service_intake_options">
                        <service-intake-options
                            @isset($referral_conditions)
                                :selected_referral_conditions='{{ json_encode($referral_conditions) }}'
                            @endisset
                            @isset($current_service)
                                :current_service='{{ json_encode($current_service) }}'
                            @endisset
                            @isset($service_booking)
                                :service_booking='{{ json_encode($service_booking) }}'
                            @endisset
                            @isset($booking_conditions)
                                :selected_booking_conditions='{{ json_encode($booking_conditions) }}'
                            @endisset
                            @isset($e_referral_conditions)
                                :selected_e_referral_conditions='{{ json_encode($e_referral_conditions) }}'
                            @endisset
                            @isset($e_referral_forms)
                                :selected_e_referral_forms='{{ json_encode($e_referral_forms) }}'
                            @endisset
                            @isset($service_booking_questions)
                                :service_booking_questions='{{ json_encode($service_booking_questions) }}'
                            @endisset
                        ></service-intake-options>
                    </div>

                    <div class="tab-pane" id="service_client_history">
                        <service-client-history
                            @isset($current_service)
                                :current_service='{{ json_encode($current_service) }}'
                            @endisset
                        ></service-client-history>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include ('service.request_additional_modal')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
@endsection

@section('scripts')
<!-- Bootstrap toogle JS -->
{{-- TinyMCE editor--}}
<script src="https://cloud.tinymce.com/dev/tinymce.min.js?apiKey={{ env('TYTINYMCE_KEY') }}" ></script>
{{-- Main logic of the file --}}
<script src="/js/service_management.js?id={{ str_random(6) }}"></script>
{{-- Opens pop-up window to request additional legal matters or eligibilities --}}
<script src="/js/request_service_vue.js?id={{ str_random(6) }}"></script>
@endsection