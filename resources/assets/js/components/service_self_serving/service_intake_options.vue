<template>
        <div class="panel-group accordion margin-bottom-0" id="sio_accordion">
            <!-- Referral settings -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title font-purple-soft bold uppercase">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#sio_referral_settings_accordion" href="#sio_referral_settings"> WHO CAN REFER TO THIS SERVICE? </a>
                    </h4>
                </div>
                <div id="sio_referral_settings" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group col-sm-12">
                            <p class=" margin-bottom-10">Enable or disable referrals from these service providers listed below.</p>
                            <p class=" margin-bottom-10">
                                If you don't want any LHO users to send service details directly to clients (eg this service is for bookings or e-referrals only),
                                simply click 'clear' to remove all organisations from this list.
                                This will prevent users from able to send an SMS or email referral to clients for this service.
                                To add all the service providers back again, click Select All and save.
                                <span id="count_referral_conditions">({{ selected_service_providers.length }}) </span> &nbsp;
                                <a href="javascript:;" class="btn btn-xs grey-mint" @click="select_all_referrals_by_scope('VLA')">Select VLA</a> &nbsp;
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
                                        @input="$parent.changeInForm"
                                        ></multiselect>
                        </div>

                    </div>
                </div>
            </div>
            <!-- END: Referral settings -->

            <!-- Booking Settings -->
            <!-- <div class="panel panel-default" v-if="current_service.hasOwnProperty('ServiceProviderTypeName') && current_service.ServiceProviderTypeName === 'VLA'"> -->
            <div class="panel panel-default" >
                <div class="panel-heading">
                    <h4 class="panel-title font-purple-soft bold uppercase">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#sio_booking_settings"> WHO CAN BOOK APPOINTMENTS AT THIS SERVICE? </a>
                    </h4>
                </div>
                <div id="sio_booking_settings" class="panel-collapse collapse">
                    <div class="panel-body">

                        <p class="margin-left-10">
                            This is a VLA-only function at this stage. If you are interested in setting up direct bookings get in touch with the LHO team: lho@vla.vic.gov.au 
                        </p>
                        <span v-if="!current_service.hasOwnProperty('ServiceId')">To enable bookings you must save this service first.</span>

                        <div v-else-if="!is_bookable" class="form-group col-sm-12 margin-0" id="service_booking">
                            <a href="#" class="btn green" @click="activate_service_booking()">Enable bookings</a>
                        </div>

                        <div v-if="is_bookable" class="form-group col-sm-12 service_booking_conditions">

                            <p class=" margin-bottom-10">Enable bookings to specific Service Providers by adding them here.
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
                                        @input="$parent.changeInForm"
                                        ></multiselect>

                            <!-- Booking Questions -->
                            <div class="margin-top-15">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title font-purple-soft bold uppercase">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#sio_booking_questions"> Booking Questions </a>
                                        </h4>
                                    </div>
                                    <div id="sio_booking_questions" class="panel-collapse collapse">
                                        <div class="panel-body">

                                            <p class=" margin-bottom-10">Questions without value or operator won't be saved.</p>

                                            <div class="form-group col-sm-12" v-for="question in service_booking_questions_mapped" :key='question.QuestionId'>
                                                <div class="col-sm-4 col-md-3">
                                                    <label class="pull-right" v-if="question.QuestionName != ''">
                                                        {{question.QuestionName}}
                                                    </label>
                                                    <label class="pull-right" v-else>
                                                        {{question.QuestionLabel}}
                                                    </label>
                                                </div>
                                                <div class="col-sm-3 col-md-2">
                                                    <select  class="form-control" v-model="question.Operator">
                                                        <option></option>
                                                        <option v-for="operator in operators" :key="operator.value" v-bind:value="operator.value"> {{ operator.label }}</option>
                                                    </select>

                                                </div>
                                                <div class="col-sm-5 col-md-3">

                                                    <vue-tags-input
                                                        v-model="question.newTag"
                                                        :tags="question.QuestionValueTag"
                                                        :add-on-key="[13, ':', ';', ',']"
                                                        placeholder=""
                                                        @tags-changed="newTags => question.QuestionValueTag = newTags"
                                                        v-if="question.QuestionTypeName == 'multiple'"
                                                        />
                                                    <input type="text" class="form-control" v-model="question.QuestionValue" v-else id="answer"  value="" >

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                                <!-- <p class="caption-subject font-purple-soft bold uppercase margin-bottom-0"></p> -->

                            <!-- End: Booking Questions -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Booking Settings -->

            <!-- E-Referral Settings -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title font-purple-soft bold uppercase">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#sio_e_ref_settings"> WHO CAN E-REFER TO THIS SERVICE? </a>
                    </h4>
                </div>
                <div id="sio_e_ref_settings" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group col-sm-12">

                            <p class=" margin-bottom-10">
                                E-referrals allow other organisations in LHO to refer help-seekers directly to your office after they’ve done some of the legal triage work on your behalf. You can customise the questions asked and the information collected to ensure you only receive correct referrals. You also save clients from ringing around and you staff from asking the same questions!
                            </p>
                            <p class=" margin-bottom-10">
                                If you have an established e-referral arrangement with a service provider, these are displayed below. 
                                <span id="count_e_referral_conditions">({{ selected_e_referral_service_providers.length }}) </span> &nbsp;
                                <a href="javascript:;" class="btn btn-xs grey-mint" @click="select_all_e_referrals_by_scope('VLA')">Select VLA</a> &nbsp;
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
                                        @input="$parent.changeInForm"
                                        ></multiselect>
                        </div>

                        <div class="form-group col-sm-12">
                            <p class=" margin-bottom-10">If no templates appear here and you would like to streamline your intake processes, get in touch with the LHO team: lho@vla.vic.gov.au </p>

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
                                        @input="$parent.changeInForm"
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
    import VueTagsInput from '@johmun/vue-tags-input';

    Vue.component('multiselect', Multiselect);

    export default {
        components: {
            VueTagsInput,
        },
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
            },
            service_booking_questions: {
                type: Array,
                default: function () { return [] }
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
                selected_booking_questions: [],
                initial_intake_options: {},
                activate_service_url: '/service_booking/activate_service',
                sb_activated: false,
                service_booking_questions_mapped: [], //This removes the multi select issue with reactive props
                operators: [
                                { label: 'Is greater than',value: '>' },
                                { label: 'Is greater or equal than', value: '>=' },
                                { label: 'Is less than', value: '<' },
                                { label: 'Is less or equal than', value: '<=' },
                                { label: 'Is equal to', value: '=' },
                                { label: 'Is not Equal to', value: '!=' },
                                { label: 'Is one of', value: 'in' }
                            ]
            };
        },

        methods: {
            pre_select_booking_questions() {
                let self = this;
                let cs_booking_questions = self.current_service.ServiceBookingQuestions;
                self.service_booking_questions.map( sb_question => {
                    sb_question.Operator = '';
                    sb_question.QuestionValue = '';
                    sb_question.newTag = '';
                    sb_question.QuestionValueTag = [];
                    cs_booking_questions.map( cs_question => {
                        if(sb_question.QuestionId === cs_question.QuestionId) {
                            sb_question.Operator = cs_question.Operator;
                            sb_question.QuestionValue = cs_question.QuestionValue;
                            if(sb_question.QuestionTypeName=='multiple'){
                                sb_question.QuestionValueTag = sb_question.QuestionValue.split(',').map(tag => { return { 'text': tag } });
                                sb_question.QuestionValue = '';
                            }
                        }
                    });
                    self.service_booking_questions_mapped.push(sb_question); //This removes the multi select issue with reactive props
                });
            },
            get_booking_questions() {
                let self = this;
                let booking_questions = [];
                let cs_booking_questions = self.service_booking_questions_mapped;

                cs_booking_questions.map( cs_question => {
                    if(cs_question.Operator != '' && (cs_question.QuestionValue != '' || cs_question.QuestionValueTag.length > 0)) {
                        let bq_value = {
                            'QuestionId': cs_question.QuestionId,
                            'Operator': cs_question.Operator,
                            'QuestionValue': cs_question.QuestionValue,
                        };
                        if(cs_question.QuestionTypeName=='multiple'){
                            bq_value.QuestionValue = cs_question.QuestionValueTag.map(tag => tag.text ).join(',');
                        }
                        booking_questions.push(bq_value);
                    }
                });
                return booking_questions;
            },
            load_e_referral_forms() {
                let self = this;
                let url = "/e_referral/listFormsFormated";

                axios['get'](url)
                .then(response => {
                    self.e_referral_forms = response.data.data;
                    self.selected_e_referral_templates = self.pre_select_conditions(self.e_referral_forms, self.selected_e_referral_forms);
                    self.initial_intake_options = Object.assign({}, self.get_intake_options());
                })
                .catch(error => {
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
                self.$parent.submit('post', self.activate_service_url, { id })
                    .then(function (response) {
                        $('#contentLoading').modal('hide');
                        if (response.success == 'success') {
                            swal("Done!", "Your service has been activated, now you need to set availability in the manage bookings page.", "success");
                            self.sb_activated = true;
                        } else {
                            swal("Error", 'Unfortunately we cannot process your request right now. Please contact a system admin for futher information.', "error");
                        }
                    }).catch(function (error) {
                        $('#contentLoading').modal('hide');
                    });
            },
            get_intake_options() {
                return  {
                    sv_id: this.current_service.ServiceId,
                    referral_conditions: this.selected_service_providers.map(item => item.id),
                    booking_conditions: this.selected_booking_service_providers.map(item => item.id),
                    e_referral_conditions: this.selected_e_referral_service_providers.map(item => item.id),
                    e_referral_forms: this.selected_e_referral_templates.map(item => item.id),
                    booking_question: this.get_booking_questions()
                };
            },
            save_intake_options() {
                $('#contentLoading').modal('show');
                let intake_options = this.get_intake_options();
                let url = '/service/intake_options';
                this.$parent.submit('post',url, intake_options)
                    .then(response => {
                        this.$parent.voidChangeInForm();
                        $('#contentLoading').modal('hide');
                        this.$parent.swal_messages(response.success, response.message);
                    })
                    .catch(error => {
                    });
            },
            event_on_change_tab() {
                let self = this;
                EventBus.$on('CHANGE_TAB_INTAKE_OPTIONS', function (payLoad) {

                    if(!Object.compare(self.get_intake_options(), self.initial_intake_options)){
                        self.$swal({
                            title: 'Save changes?',
                            text: "Do not miss any modification you have made so far",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#17c4bb',
                            cancelButtonColor: '#e2e5ec',
                            confirmButtonText: 'Yes',
                            allowEscapeKey: false,
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.value) {
                                //Call save method of current tab
                                self.save_intake_options();
                            }
                        });
                    }

                });
            },
            select_all_referrals_by_scope(scope) {
                this.selected_service_providers =
                    this.service_providers.filter(item => (item.type === scope || (item.type === 'Legal Help' && scope === 'VLA')));
            },
            select_all_e_referrals_by_scope(scope) {
                this.selected_e_referral_service_providers =
                    this.service_providers.filter(item => (item.type === scope || (item.type === 'Legal Help' && scope === 'VLA')));
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
            this.pre_select_booking_questions();
            this.event_on_change_tab();
        },
    }
</script>