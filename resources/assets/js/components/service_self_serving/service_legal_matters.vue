<template>
    <div class="form">
        <div class="form-horizontal">
            <div class="form-group">

                <div class="col-xs-5">
                    <p class="caption-subject font-purple-soft bold uppercase">Legal Matters</p>
                </div>

                <div class="col-sm-12">
                    <p><small>Choose the legal matters covered by the service. If needed <a data-toggle="modal" href="#request-matter">request a new legal matter</a>.</small></p>
                    <multiselect
                    v-model="matters_selected"
                    label="text"
                    key="id"
                    id="matters"
                    track-by="id"
                    open-direction="bottom"
                    placeholder="Select Legal Matters"
                    :options='matters'
                    :multiple="true"
                    :searchable="true"
                    :close-on-select="true"
                    :show-no-results="false"
                    :show-labels="false"
                    >
                    </multiselect>


                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-5">
                    <p class="caption-subject font-purple-soft bold uppercase">Legal Matter Conditions</p>
                </div>
                <div class="col-sm-12">
                    <p><small>Add a condition to a legal matter or override the eligibility criteria for a specific Legal Matter in this service </small></p>
                    <p><small>Use the tabs below to add Conditions to a specific legal matter or override the service-wide eligibility criteria for each Legal Matter </small></p>
                    <ul class="nav nav-tabs">
                        <li v-bind:class="[ { active: (currentTab === matter || (!currentTab.hasOwnProperty('id') && index === 0) )}]"
                            v-on:click="currentTab = matter" v-for="(matter, index) in matters_selected" :key='matter.id' >
                            <a data-toggle="tab" :href="'#'+ matter.id">{{matter.text}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <p>To narrow down Legal Matters to specific conditions add an operator and a value to applicable conditions. If the condition is not visible below it can be added from the 'Legal Matter Conditions' page. Values can either be numbers if the condition is numerical (EG: Fines > 4000) or 'true/false' if the condition is a boolean (EG: Has court date = true)</p>
                        <div v-for="(matter, index) in matters_selected"  :key='matter.id' :id="matter.id" v-bind:class="['tab-pane', { active: (currentTab === matter || (!currentTab.hasOwnProperty('id') && index === 0)) }]">
                            <div class="form-group col-md-12" v-for="question in matter.questions" :key='question.QuestionId'>
                                <div class="col-md-5">
                                    <label class="pull-right" v-if="question.QuestionName != ''">
                                        {{question.QuestionName}}
                                    </label>
                                    <label class="pull-right" v-else>
                                        {{question.QuestionLabel}}
                                    </label>
                                </div>
                                <div class="col-md-2">
                                    <select  class="form-control" v-model="question.Operator">
                                        <option></option>
                                        <option v-for="operator in operators" :key="operator.value" v-bind:value="operator.value"> {{ operator. label }}</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" data-role=tagsinput v-model="question.QuestionValue" v-if="question.QuestionTypeName == 'multiple'"  :name="`question[${question.MatterId}][${question.QuestionId}][answer]`" id="answer"  value="" >
                                    <input type="text" class="form-control" v-model="question.QuestionValue" v-else :name="`question[${question.MatterId}][${question.QuestionId}][answer]`" id="answer"  value="" >
                                </div>
                            </div>
                            <hr>
                            <div class="col-xs-5">
                                <p class="caption-subject font-purple-soft bold uppercase">Eligibility Criteria</p>
                            </div>
                            <div class="col-sm-12">
                                <p>Override the service-wide eligibility criteria by selecting ALL that apply for this legal matter below. Any checkboxes selected or not selected here will override the service-wide eligibility criteria for this service. Ensure that any service-wide eligibility criteria that still apply for this legal matter are selected again below.</p>
                            </div>
                            <div class="col-sm-12">
                                <multiselect
                                v-model="matter.questions_selected"
                                label="QuestionLabel"
                                key="QuestionId"
                                id="vulnerability"
                                track-by="QuestionLabel"
                                placeholder="Select eligibility..."
                                open-direction="top"
                                :options="eligibility_questions"
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
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="button" class="btn green margin-top-15" @click="save_legal_matters()">Save Legal Matters</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import moment from 'moment'
    import axios from 'axios';
    import EventBus from '../../utils/event-bus';


	export default {
        props: {
            current_service : {
                type: Object,
                required: false,
                default: function () { return {} }
            },
            eligibility_questions : {
                type: Array,
                required:true,
            }

        },
        data () {
            let self = this;
            return {
                    matters_selected: [],
                    matters: [],
                    currentTab:{},
                    operators:[ {label:'>',value:'>'},
                                {label:'>=', value:'>='},
                                {label:'<', value:'<'},
                                {label:'<=', value:'<='},
                                {label:'Equal', value:'='},
                                {label:'Not Equal', value:'!='},
                                {label:'IN', value:'in'},
                                ]

            }
        },
        methods: {
            init_legal_matters: function() {
                let self = this;
                axios.get('/matter/listWithQuestionsFormated' )
                    .then(function (response) {
                        self.matters = response.data;
                        if(self.current_service.hasOwnProperty('ServiceMatters') && self.current_service.ServiceMatters.length > 0){
                            self.current_service.ServiceMatters.forEach(service_matter => {
                                self.matters.forEach(matter => {
                                    if(service_matter.MatterID == matter.id){
                                        self.matters_selected.push(matter);
                                    }
                                });
                            });
                        }
                        self.setLegalMatterValues();

                    })
                    .catch(function (error) {
                        self.init_legal_matters();
                        console.log(error);
                    });
            },
            setLegalMatterValues: function() {
                let self = this;
                // If no prevous value saved - Show default value
                self.matters_selected.forEach(matter => {
                    matter.questions.forEach(question => {
                        // Put the default value Get previous answer if exist
                        if(self.current_service){
                            // Legal Matter Conditions
                            self.current_service.ServiceMatters.forEach(service_matter => {
                                if(service_matter.MatterAnswers){
                                    let questions_id = service_matter.MatterAnswers.map(item => item.QuestionId);
                                    let question_index = questions_id.indexOf(question.QuestionId);
                                    if(question_index !== -1 && service_matter.MatterID == matter.id){
                                        let current_answer = service_matter.MatterAnswers[question_index];
                                        question.Operator = current_answer.Operator;
                                        question.QuestionValue = current_answer.QuestionValue;
                                    }
                                }
                            });

                        }
                    });
                    // Eligibility Criteria - Vulnerability Questions
                    if(self.current_service){
                        self.current_service.ServiceMatters.forEach(service_matter => {
                            if(service_matter.MatterID == matter.id
                                && service_matter.VulnerabilityMatterAnswers
                                && service_matter.VulnerabilityMatterAnswers.length > 0){
                                let current_lm_vulnerabilities = service_matter.VulnerabilityMatterAnswers.map( item => {
                                    if(item.Operator=="=" && item.QuestionValue=="1"){
                                        return item.QuestionId;
                                    }
                                });
                                self.eligibility_questions.forEach(eligibility_question => {
                                    if(current_lm_vulnerabilities.indexOf(eligibility_question.QuestionId)  != -1 ){
                                        matter.questions_selected.push(eligibility_question);
                                    }
                                });
                            }
                        });
                    }
                });
            },
            save_legal_matters() {
                $('#contentLoading').modal('show');
                let legal_matters = {
                    sv_id: this.current_service.ServiceId,
                    matters: this.matters_selected,
                };
                let url = '/service/legal_matter';
                this.submit_service_lm('post',url, legal_matters)
                .then(response => {
                    $('#contentLoading').modal('hide');
                    this.swal_messages(response.success, response.message);
                })
                .catch(error => {
                    console.log(error);
                });
            },
            event_on_change_tab() {
                let self = this;
                EventBus.$on('CHANGE_TAB_MATTERS', function (payLoad) {
                    self.save_legal_matters();
                });
            },
            submit_service_lm(requestType, url, data) {
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

        },
        watch:{

        },
        created() {
            this.init_legal_matters();
            this.event_on_change_tab();
        },
    }

</script>
