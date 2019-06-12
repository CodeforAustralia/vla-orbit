<template>
    <div class="form">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-xs-12">
                    <p class="caption-subject font-purple-soft bold uppercase margin-bottom-10">Service client matters</p>
                    <!-- Eligibility Questions -->
                    <p class="margin-bottom-0">
                        <small>
                            These eligibility criteria are threshold requirements for entry into the service. 
                            The chosen criteria are service wide and will be overridden is a condition is added. 
                            If needed <a data-toggle="modal" href="#request-vulnerability">request a new criteria</a>.
                        </small>
                    </p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <multiselect
                    v-model="selected_questions"
                    label="QuestionLabel"
                    key="QuestionId"
                    id="vulnerability"
                    track-by="QuestionLabel"
                    placeholder="Select eligibility..."
                    open-direction="bottom"
                    :options="eligibility_questions"
                    :multiple="true"
                    :searchable="true"
                    :close-on-select="true"
                    :show-no-results="false"
                    :show-labels="false"
                    ></multiselect>
                </div>
            </div>
            <!-- End: Eligibility Questions -->

            <!-- Booking Questions -->
            <div class="form-group">
                <div class="col-xs-12">
                    <p class="caption-subject font-purple-soft bold uppercase margin-bottom-0">Booking Questions</p>
                </div>
            </div>

            <div class="form-group col-sm-12" v-for="question in service_booking_questions" :key='question.QuestionId'>
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
                        <option v-for="operator in operators" :key="operator.value" v-bind:value="operator.value"> {{ operator.label }}</option>
                    </select>
                </div>
                <div class="col-sm-5 col-md-3">
                    <input type="text" class="form-control" v-model="question.QuestionValue" v-if="question.QuestionTypeName == 'multiple'" data-role=tagsinput>
                    <input type="text" class="form-control" v-model="question.QuestionValue" v-else>
                </div>
            </div>
            <!-- End: Booking Questions -->

            <div class="form-group">
                <div class="col-sm-12">
                    <button type="button" class="btn green margin-top-15" @click="save_client_matters()">Save Client Matters</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Vue from 'vue';
    import axios from 'axios';
    import Multiselect from 'vue-multiselect';
    import EventBus from './../../utils/event-bus';
    import Object from '../../utils/compare_objects';
    Vue.component('multiselect', Multiselect);

	export default {
        props: {
            eligibility_questions: {
                required: true,
                type: Array
            },
            selected_eligibility_questions: {
                type: Array,
                default: function () { return [] }
            },
            current_service: {
                default: function () { return {} }
            },
            service_booking_questions: {
                type: Array,
                default: function () { return [] }
            }
        },
        data () {
            return {
                selected_questions: [], //Those are vulnerability/eligibility questions
                selected_booking_questions: [],
                selected_operator: [],
                initial_client_matters: {},
                operators: [
                                { label: '>',value: '>' },
                                { label: '>=', value: '>=' },
                                { label: '<', value: '<' },
                                { label: '<=', value: '<=' },
                                { label: 'Equal', value: '=' },
                                { label: 'Not Equal', value: '!=' },
                                { label: 'IN', value: 'in' }
                            ]
            };
        },
        methods: {
            pre_select_questions() {
                let self = this;
                self.selected_questions = self.eligibility_questions.filter(question => {
                    for (let index = 0; index < self.selected_eligibility_questions.length; index++) {
                        if(question.QuestionId == self.selected_eligibility_questions[index]) {
                            return true;
                        }
                    }
                    return false;
                });
            },
            pre_select_booking_questions() {
                let self = this;
                let cs_booking_questions = self.current_service.ServiceBookingQuestions;
                self.service_booking_questions.map( sb_question => {
                    sb_question.Operator = '';
                    sb_question.QuestionValue = '';
                    cs_booking_questions.map( cs_question => {
                        if(sb_question.QuestionId === cs_question.QuestionId) {
                            sb_question.Operator = cs_question.Operator;
                            sb_question.QuestionValue = cs_question.QuestionValue;
                        }
                    });
                });
            },
            set_initial_client_matters() {
                this.initial_client_matters = Object.assign({}, this.get_client_matters());
            },
            get_booking_questions() {
                let self = this;
                let booking_questions = [];
                let cs_booking_questions = self.service_booking_questions;

                cs_booking_questions.map( cs_question => {
                    if(cs_question.Operator != '' && cs_question.QuestionValue != '') {
                        booking_questions.push({
                            'QuestionId': cs_question.QuestionId,
                            'Operator': cs_question.Operator,
                            'QuestionValue': cs_question.QuestionValue,
                        });
                    }
                });
                return booking_questions;
            },
            get_client_matters(){
                return {
                    sv_id: this.current_service.ServiceId,
                    vulnerability: this.selected_questions.map(item => item.QuestionId),
                    booking_question: this.get_booking_questions()
                };
            },
            save_client_matters() {
                $('#contentLoading').modal('show');
                let client_matters = this.get_client_matters();
                let url = '/service/client_eligibility';
                this.$parent.submit('post',url, client_matters)
                    .then(response => {
                        $('#contentLoading').modal('hide');
                        this.$parent.swal_messages(response.success, response.message);
                        this.initial_client_matters = Object.assign({}, this.get_client_matters());
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            event_on_change_tab() {
                let self = this;
                EventBus.$on('CHANGE_TAB_ELIGIBILITY', function (payLoad) {
                    if(!Object.compare(self.get_client_matters(), self.initial_client_matters)){
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
                                self.save_client_matters();
                            }
                        });
                    }
                });
            }
        },
        created() {
            this.pre_select_questions();
            this.pre_select_booking_questions();
            this.set_initial_client_matters();
            this.event_on_change_tab();
        },
    }
</script>