<template>
    <div class="form">
        <div class="form-horizontal">
            <div class="form-group">

                <div class="col-xs-5">
                    <p class="caption-subject font-purple-soft bold uppercase">Legal Matters</p>
                </div>

                <div class="col-sm-12">
                    <p>
                        <small>Choose the legal matters covered by the service. If needed <a data-toggle="modal" href="#request-matter">request a new legal matter</a>.</small>
                        <a href="javascript:;" class="btn btn-xs green" @click="matters_selected = matters">Select All</a> &nbsp;
                        <a href="javascript:;" class="btn btn-xs red" @click="matters_selected = []">Clear</a>
                    </p>
                    <p>
                        <small>
                            For more information you can download a copy of all the legal matters 
                            <a href="/docs/Legal Matters with area and group 14112019.xlsx" download target="_blank">
                                here
                            </a>.
                        </small>
                    </p>
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
                        @remove="removeLegalMatter"
                        @input="changeInForm()"
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
                    <p><small>Use the options below to add Conditions to a specific legal matter or override the service-wide eligibility criteria for each Legal Matter </small></p>
                    <multiselect
                    v-model="matter_selected"
                    label="text"
                    key="id"
                    id="matters"
                    track-by="id"
                    open-direction="bottom"
                    placeholder="Select a Legal Matter"
                    :options='matters_selected'
                    :multiple="false"
                    :searchable="true"
                    :close-on-select="true"
                    :show-no-results="true"
                    :show-labels="false"
                    @input = "updateCurrentTab"
                    >
                    </multiselect>
                    <div class="tab-content">
                        <p class="margin-bottom-0">
                            <small>
                                To narrow down Legal Matters to specific conditions add an operator and a value to applicable conditions. If the condition is not visible below it can be added from the 'Legal Matter Conditions' page. Values can either be numbers if the condition is numerical (EG: Fines > 4000) or 'true/false' if the condition is a boolean (EG: Has court date = true)
                            </small>
                        </p>
                        <div v-for="matter in matters_selected"  :key='matter.id' :id="matter.id" v-bind:class="['tab-pane', { active: (currentTab === matter) }]">
                            <div class="form-group col-md-12 question-group" v-for="(question, index) in matter.questions" :key='question.QuestionId'>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label :class="'pull-right ' + (question.QuestionName.length > 56 ? 'long-text-label' : '')" v-if="question.QuestionName != ''">
                                            {{question.QuestionName}}
                                        </label>
                                        <label :class="'pull-right ' + (question.QuestionLabel.length > 56 ? 'long-text-label' : '')" v-else>
                                            {{question.QuestionLabel}}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <select  class="form-control" v-model="question.Operator">
                                            <option></option>
                                            <option v-for="operator in operators" :key="operator.value" v-bind:value="operator.value"> {{ operator. label }}</option>
                                        </select>
                                        <small v-show="validate_operator(question.Operator, question.QuestionValue, question.QuestionValueTag)" class="red">Please provide an operator</small>
                                    </div>
                                    <div class="col-md-5">
                                        <vue-tags-input
                                            v-model="question.tag"
                                            :tags="question.QuestionValueTag"
                                            :add-on-key="[13, ':', ';', ',']"
                                            placeholder=""
                                            @tags-changed="newTags => question.QuestionValueTag = newTags"
                                            v-if="question.QuestionTypeName == 'multiple'"
                                            />
                                        <input type="text" class="form-control" v-model="question.QuestionValue" v-else id="answer"  value="" >
                                        <small v-show="validate_value(question.Operator, question.QuestionValue, question.QuestionValueTag)" class="red">Please provide a value</small>
                                    </div>
                                </div>
                                <hr class="col-sm-12 margin-hr" v-if="index < matter.questions.length-1">
                            </div>

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
                                    @input="changeInForm()"
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
    import Object from '../../utils/compare_objects';
    import VueTagsInput from '@johmun/vue-tags-input';

	export default {
        components: {
            VueTagsInput,
        },
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
            return {
                matters_selected: [],
                matter_selected: [],
                matters: [],
                currentTab:{},
                operators:[ {label:'>',value:'>'},
                            {label:'>=', value:'>='},
                            {label:'<', value:'<'},
                            {label:'<=', value:'<='},
                            {label:'Equal', value:'='},
                            {label:'Not Equal', value:'!='},
                            {label:'IN', value:'in'},
                            ],
                tag: '',
                initial_lm: [],
                modified: false
            }
        },
        methods: {
            changeInForm: function() {
                this.modified = true;
                console.log("Modified");
            },
            avoidLeaveWithoutChanges: function() {
                let self = this;
                //Validate before leaving the current tab except if it the user is submiting the form.
                window.onbeforeunload = function (e) {
                    if (self.modified) {
                        e.preventDefault();
                        var message = "You have not saved your changes.", e = e || window.event;
                        if (e) {
                            e.returnValue = message;
                        }
                        return message;
                    }
                }
            },
            validate_value(operator, questionValue, questionValueTag){
                if( ((questionValueTag && questionValueTag.length < 1) || questionValue == '')
                        && operator != ''){
                    return true;
                }
                return false;
            },
            validate_operator(operator, questionValue, questionValueTag){
                if( ((questionValueTag && questionValueTag.length > 0) || questionValue != '')
                        && operator == ''){
                    return true;
                }
                return false;
            },
            init_legal_matters: function() {
                let self = this;
                axios.get('/matter/listWithQuestionsFormated' )
                    .then(function (response) {
                        self.matters = response.data;
                        if(self.current_service.hasOwnProperty('ServiceMatters') && self.current_service.ServiceMatters.length > 0){
                            self.current_service.ServiceMatters.map(service_matter => {
                                self.matters.map(matter => {
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
                self.matters_selected.map(matter => {
                    matter.questions.map(question => {
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
                                        // for multiple answer question
                                        if(question.QuestionTypeName=='multiple'){
                                            let temp_array = current_answer.QuestionValue.split(",");
                                            question.QuestionValueTag = [];
                                            temp_array.forEach(tag => {
                                                question.QuestionValueTag.push({
                                                    'text': tag,
                                                })
                                            });
                                        }
                                    }
                                }
                            });

                        }
                    });
                    // Eligibility Criteria - Vulnerability Questions
                    self.setEligibilityCriteria(matter);
                });
                self.set_initial_lm();
            },
            setEligibilityCriteria(matter) {
                let self = this;
                if(self.current_service){
                    self.current_service.ServiceMatters.map(service_matter => {
                        if(service_matter.MatterID == matter.id
                            && service_matter.VulnerabilityMatterAnswers
                            && service_matter.VulnerabilityMatterAnswers.length > 0){
                            let current_lm_vulnerabilities = service_matter.VulnerabilityMatterAnswers.map( item => {
                                if(item.Operator=="=" && item.QuestionValue=="1"){
                                    return item.QuestionId;
                                }
                            });
                            self.eligibility_questions.map(eligibility_question => {
                                if(current_lm_vulnerabilities.indexOf(eligibility_question.QuestionId)  != -1 ){
                                    matter.questions_selected.push(eligibility_question);
                                }
                            });
                        }
                    });
                }
            },
            set_initial_lm() {
                this.initial_lm = this.get_lm();
            },
            get_lm() {
                let self = this;
                let lm = {
                    sv_id: self.current_service.ServiceId,
                    matters: self.matters_selected,
                };
                return JSON.parse(JSON.stringify(lm)); //This is the slowest option but is the only one working with arrays
            },
            save_legal_matters() {
                let self = this;
                if(this.validateLegalMatters()){
                    $('#contentLoading').modal('show');
                    let legal_matters = self.get_lm();
                    let url = '/service/legal_matter';
                    self.$parent.submit('post',url, legal_matters)
                    .then(response => {
                        this.modified = false;
                        $('#contentLoading').modal('hide');
                        self.$parent.swal_messages(response.success, response.message);
                        self.initial_lm = self.get_lm();
                    })
                    .catch(error => {
                        console.log(error);
                    });
                }
            },
            event_on_change_tab() {
                let self = this;
                EventBus.$on('CHANGE_TAB_MATTERS', function (payLoad) {
                    if(!Object.compare(self.get_lm(), self.initial_lm)){
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
                                self.save_legal_matters();
                            }
                        });
                    }

                });
            },
            validateLegalMatters() {
                let self = this;
                let message = '';
                let result = true;
                if(self.matters_selected) {
                    self.matters_selected.map(matter => {
                        if(matter.questions){
                            matter.questions.map(question => {
                                if(question.Operator && !question.QuestionValue && question.QuestionTypeName != 'multiple') {
                                    message = "Please select a value for question '" + question.QuestionName + "'";
                                }
                                if(!question.Operator && question.QuestionValue && question.QuestionTypeName != 'multiple') {
                                    message = "Please select an operator for question '" + question.QuestionName + "'";
                                }
                                if(!question.Operator && question.QuestionValueTag && question.QuestionValueTag.length > 0 && question.QuestionTypeName == 'multiple') {
                                    message = "Please select an operator for question '" + question.QuestionName + "'";
                                }
                                if(question.Operator && (!question.QuestionValueTag || question.QuestionValueTag.length <= 0) && question.QuestionTypeName == 'multiple') {
                                    message = "Please select a value for question '" + question.QuestionName + "'";
                                }
                            });
                        }
                    });
                }
                if(message) {
                    self.$parent.swal_messages("error", message);
                    result = false;
                }
                return result;
            },
            updateCurrentTab(matter_selected){
                let self = this;
                if(matter_selected){
                    self.currentTab = matter_selected;
                } else {
                    self.currentTab = {};
                }
                this.changeInForm();
            },
            removeLegalMatter(matter_removed){
                let self = this;
                if(matter_removed == self.currentTab) {
                    self.matter_selected = {};
                }
            },

        },
        created() {
            let self = this;
            this.init_legal_matters();
            this.event_on_change_tab();
            this.avoidLeaveWithoutChanges();
        },
    }

</script>

<style>
    .long-text-label {
        line-height: initial !important;
        text-align: justify;
    }

    .margin-hr {
        margin-bottom: 5px;
    }
</style>