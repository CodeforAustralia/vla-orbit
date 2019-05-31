<template>
    <div class="row">
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
                name="matters[]"
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
                    <li v-bind:class="[ { active: currentTab === matter }]"
                        v-on:click="currentTab = matter" v-for="matter in matters_selected" :key='matter.id' >
                        <a data-toggle="tab" :href="'#'+ matter.id">{{matter.text}}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <p>To narrow down Legal Matters to specific conditions add an operator and a value to applicable conditions. If the condition is not visible below it can be added from the 'Legal Matter Conditions' page. Values can either be numbers if the condition is numerical (EG: Fines > 4000) or 'true/false' if the condition is a boolean (EG: Has court date = true)</p>
                    <div v-for="matter in matters_selected" :key='matter.id' :id="matter.id" v-bind:class="['tab-pane', { active: currentTab === matter }]">
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
                                <!-- <select  class="form-control" :name="`question[${question.MatterId}][${question.QuestionId}][operator]`"  id="operator"> -->
                                <!-- </select> -->
                                <multiselect
                                v-model="question.operator_selected"
                                label="label"
                                key="value"
                                track-by="value"
                                id="operator-select"
                                placeholder="Select Operator..."
                                open-direction="bottom"
                                :options='operators'
                                :multiple="false"
                                :searchable="true"
                                :close-on-select="true"
                                :show-no-results="false"
                                :show-labels="false"
                                >
                                </multiselect>

                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" data-role=tagsinput v-model="question.QuestionValue" v-if="question.QuestionTypeName == 'multiple'"  :name="`question[${question.MatterId}][${question.QuestionId}][answer]`" id="answer"  value="" >
                                <input type="text" class="form-control" v-model="question.QuestionValue" v-else :name="`question[${question.MatterId}][${question.QuestionId}][answer]`" id="answer"  value="" >
                            </div>
                        </div>
                        <!-- {{matter.text}} -->
                    </div>
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
                default: []
            },

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
                        if(self.current_service){
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
                        // Put the default value
                        if(question.Operator && question.QuestionValue){
                            question.operator_selected = self.operators.find(operator => operator.value == question.Operator);
                        }
                        // Get previous answer if exist
                        if(self.current_service){
                            self.current_service.ServiceMatters.forEach(service_matter => {
                                if(service_matter.MatterAnswers){
                                    let questions_id = service_matter.MatterAnswers.map((value, index) => {
                                        return value["QuestionId"];
                                    });
                                    let question_index = questions_id.indexOf(question.QuestionId);
                                    if(question_index !== -1){
                                        let current_answer = service_matter.MatterAnswers[question_index];
                                        question.operator_selected = self.operators.find(operator => operator.value == current_answer.Operator);
                                        question.QuestionValue = current_answer.QuestionValue;
                                    }
                                }

                            });
                        }
                    });
                });
            },
            event_on_change_tab() {
                let self = this;
                EventBus.$on('CHANGE_TAB_MATTERS', function (payLoad) {
                    //self.save_intake_options();
                });
            }

        },
        watch:{

        },
        created() {
            this.init_legal_matters();
            this.event_on_change_tab();
        },
    }

</script>
