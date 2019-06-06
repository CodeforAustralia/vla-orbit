<template>
        <div class="panel-group accordion margin-bottom-0" id="sio_accordion">
            <!-- Referral settings -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title font-purple-soft bold uppercase">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#sio_referral_settings_accordion" href="#sio_referral_settings"> Who can refer to you? </a>
                    </h4>
                </div>
                <div id="sio_referral_settings" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group col-sm-12">
                            <p class="margin-0">Referrals conditions:</p>
                            <p class="font-grey-silver margin-bottom-10">Enable Referrals to specific Service Providers by adding them here. 
                                <span id="count_referral_conditions">({{ selected_service_providers.length }}) </span> &nbsp;
                                <a href="javascript:;" class="btn btn-xs green" @click="selected_service_providers = service_providers">Select All</a> &nbsp;
                                <a href="javascript:;" class="btn btn-xs red" @click="selected_service_providers = []">Clear</a>
                            </p>

                            <multiselect
                                        v-model="selected_service_providers"
                                        label="text"
                                        key="id"
                                        id="referral_conditions"
                                        name="referral_conditions[]"
                                        track-by="text"
                                        placeholder="Select Service provider"
                                        open-direction="bottom"
                                        :options="service_providers"
                                        :multiple="true"
                                        :searchable="true"
                                        :close-on-select="true"
                                        :show-no-results="false"
                                        :show-labels="false"
                                        ></multiselect>
                        </div>

                    </div>
                </div>
            </div>
            <!-- END: Referral settings -->

            <!-- Booking Settings {{ (isset($current_service) && $current_service->ServiceProviderTypeName == 'VLA' ? '' : 'hidden' )}}-->
            <div class="panel panel-default" v-if="current_service.hasOwnProperty('ServiceProviderTypeName') && current_service.ServiceProviderTypeName === 'VLA'">
                <div class="panel-heading">
                    <h4 class="panel-title font-purple-soft bold uppercase">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#sio_booking_settings"> Who can book to you? </a>
                    </h4>
                </div>
                <div id="sio_booking_settings" class="panel-collapse collapse">
                    <div class="panel-body">
                        <span v-if="!current_service.hasOwnProperty('ServiceId')">To enable bookings you must save this service first.</span>

                        <div v-else-if="!is_bookable" class="form-group col-sm-12 margin-0" id="service_booking">
                            <a href="#" class="btn green" @click="activate_service_booking()">Enable bookings</a>
                        </div>

                        <div v-if="is_bookable" class="form-group col-sm-12 service_booking_conditions">

                            <p class="font-grey-silver margin-bottom-10">Enable bookings to specific Service Providers by adding them here.
                                <span id="count_booking_conditions">({{ selected_booking_service_providers.length }}) </span> &nbsp;
                                <a href="javascript:;" class="btn btn-xs green" @click="selected_booking_service_providers = service_providers">Select All</a> &nbsp;
                                <a href="javascript:;" class="btn btn-xs red" @click="selected_booking_service_providers = []">Clear</a>
                            </p>

                            <multiselect
                                        v-model="selected_booking_service_providers"
                                        label="text"
                                        key="id"
                                        id="booking_conditions"
                                        name="booking_conditions[]"
                                        track-by="id"
                                        placeholder="Select Service provider"
                                        open-direction="bottom"
                                        :options="service_providers"
                                        :multiple="true"
                                        :searchable="true"
                                        :close-on-select="true"
                                        :show-no-results="false"
                                        :show-labels="false"
                                        ></multiselect>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Booking Settings -->

            <!-- E-Referral Settings -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title font-purple-soft bold uppercase">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#sio_e_ref_settings"> Who can E-Refer to you? </a>
                    </h4>
                </div>
                <div id="sio_e_ref_settings" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group col-sm-12">

                            <p class="font-grey-silver margin-bottom-10">Enable E-Referreal to specific Service Providers by adding them here.
                                <span id="count_e_referral_conditions">({{ selected_e_referral_service_providers.length }}) </span> &nbsp;
                                <a href="javascript:;" class="btn btn-xs green" @click="selected_e_referral_service_providers = service_providers">Select All</a> &nbsp;
                                <a href="javascript:;" class="btn btn-xs red" @click="selected_e_referral_service_providers = []">Clear</a>
                            </p>

                            <multiselect
                                        v-model="selected_e_referral_service_providers"
                                        label="text"
                                        key="id"
                                        id="e_referral_conditions"
                                        name="e_referral_conditions[]"
                                        track-by="id"
                                        placeholder="Select Service provider"
                                        open-direction="bottom"
                                        :options="service_providers"
                                        :multiple="true"
                                        :searchable="true"
                                        :close-on-select="true"
                                        :show-no-results="false"
                                        :show-labels="false"
                                        ></multiselect>
                        </div>

                        <div class="form-group col-sm-12">
                            <p class="font-grey-silver margin-bottom-10">Choose e-referral template available for ths service. Template chosen here are for all added service providers.</p>

                            <multiselect
                                        v-model="selected_e_referral_templates"
                                        label="text"
                                        key="id"
                                        id="e_referral_forms"
                                        name="e_referral_forms[]"
                                        track-by="id"
                                        placeholder="Select e-referral forms"
                                        open-direction="bottom"
                                        :options="e_referral_forms"
                                        :multiple="true"
                                        :searchable="true"
                                        :close-on-select="true"
                                        :show-no-results="false"
                                        :show-labels="false"
                                        ></multiselect>
                        </div>

                    </div>
                </div>
            </div>
            <!-- END: E-Referral Settings -->

            <button type="button" class="btn green margin-top-15" @click="save_intake_options()">Save Intake Options</button>

        </div>
</template>

<script>

    import Vue from 'vue';
    import axios from 'axios';
    import Multiselect from 'vue-multiselect';
    import EventBus from './../../utils/event-bus';

    Vue.component('multiselect', Multiselect);

    export default {
        props: {
            selected_referral_conditions: {
                default: function () { return [] },
                type: Array
            },
            selected_booking_conditions: {
                default: function () { return [] },
                type: Array
            },
            selected_e_referral_conditions: {
                default: function () { return [] },
                type: Array
            },
            selected_e_referral_forms: {
                default: function () { return [] },
                type: Array
            },
            current_service: {
                default: function () { return {} }
            },
            service_booking: {
                default: function () { return {} }
            }
        },

        data () {
            return {
                e_referral_forms: [],
                service_providers: [],
                selected_service_providers: [],
                selected_booking_service_providers: [],
                selected_e_referral_service_providers: [],
                selected_e_referral_templates: [],
                activate_service_url: '/service_booking/activate_service',
                sb_activated: false
            };
        },

        methods: {
            load_e_referral_forms() {
                let self = this;
                let url = "/e_referral/listFormsFormated";

                axios['get'](url)
                .then(response => {
                    self.e_referral_forms = response.data.data;
                    self.selected_e_referral_templates = self.pre_select_conditions(self.e_referral_forms, self.selected_e_referral_forms);
                })
                .catch(error => {
                    console.log('retrying: ', error);
                    self.load_e_referral_forms();
                });
            },
            load_service_providers() {
                let self = this;
                let url = "/service_provider/listFormated";
                let csrf = document.querySelector('meta[name=_token]').content;
                let data = { scope: 'All' };
                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;
                axios['post'](url, data)
                .then(response => {
                    self.service_providers = response.data;
                    self.selected_service_providers = self.pre_select_conditions(self.service_providers, self.selected_referral_conditions);
                    self.selected_booking_service_providers = self.pre_select_conditions(self.service_providers, self.selected_booking_conditions);
                    self.selected_e_referral_service_providers = self.pre_select_conditions(self.service_providers, self.selected_e_referral_conditions);
                })
                .catch(error => {
                    console.log('retrying: ', error);
                    self.load_service_providers();
                });
            },
            pre_select_conditions(complete_array, selected_options) {
                return complete_array.filter(option => {
                    for (let index = 0; index < selected_options.length; index++) {
                        if(option.id == selected_options[index]) {
                            return true;
                        }
                    }
                    return false;
                });
            },
            activate_service_booking() {
                $('#contentLoading').modal('show');
                let id = 0;
                let self = this;
                if(self.current_service.hasOwnProperty('ServiceId')){
                    //activate it with ServiceId
                    id = self.current_service.ServiceId;
                }
                self.submit_service_io('post', self.activate_service_url, { id })
                    .then(function (response) {
                        $('#contentLoading').modal('hide');
                        if (response.success == 'success') {
                            swal("Done!", "Your service has been activated, now you need to set availability in the manage bookings page.", "success");
                            self.sb_activated = true;
                        } else {
                            console.log('Error: ', response.message);
                            swal("Error", 'Unfortunately we cannot process your request right now. Please contact a system admin for futher information.', "error");
                        }
                    }).catch(function (error) {
                        $('#contentLoading').modal('hide');
                        console.log('Error: ', error)
                    });
            },
            save_intake_options() {
                $('#contentLoading').modal('show');
                let intake_options = {
                    sv_id: this.current_service.ServiceId,
                    referral_conditions: this.selected_service_providers.map(item => item.id),
                    booking_conditions: this.selected_booking_service_providers.map(item => item.id),
                    e_referral_conditions: this.selected_e_referral_service_providers.map(item => item.id),
                    e_referral_forms: this.selected_e_referral_templates.map(item => item.id),
                };
                let url = '/service/intake_options';
                this.submit_service_io('post',url, intake_options)
                .then(response => {
                    $('#contentLoading').modal('hide');
                    this.swal_messages(response.success, response.message);
                })
                .catch(error => {
                    console.log(error);
                });
            },
            /**
             * Submit Client Intake information or activate booking - general axios submit function
             * @param {string} requestType post, get, patch, update
             * @param {string} url End point to submit from
             * @param {data} object with information of intake options
             */
            submit_service_io(requestType, url, data) {
                return new Promise((resolve, reject) => {
                    //Do Ajax or Axios request
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest'
                    };
                    axios[requestType](url, data)
                        .then(response => {
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response.data);
                    });
                });
            },
            swal_messages(type, message) {
                this.$swal(type.charAt(0).toUpperCase() + type.slice(1), message, type);
            },
            event_on_change_tab() {
                let self = this;
                EventBus.$on('CHANGE_TAB_INTAKE_OPTIONS', function (payLoad) {
                    self.save_intake_options();
                });
            }
        },
        computed: {
            is_bookable(){
                return (this.current_service.hasOwnProperty('ServiceId') && this.service_booking.hasOwnProperty('RefNo') ) || this.sb_activated;
            }
        },
        created() {
            this.load_service_providers();
            this.load_e_referral_forms();
            this.event_on_change_tab();
        },
    }
</script>