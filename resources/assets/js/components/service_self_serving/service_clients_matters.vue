<template>
    <div class="form">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-xs-12">
                    <p class="caption-subject font-purple-soft bold uppercase margin-bottom-10">Service client matters</p>
                    <!-- Eligibility Questions -->
                    <!-- <p class="margin-bottom-0">
                        <small>
                            These eligibility criteria are threshold requirements for this service to appear in LHO search results. 
                            The chosen criteria are service wide and will be overridden is a condition is added. 
                            If needed <a data-toggle="modal" href="#request-vulnerability">request a new criteria</a>.
                        </small>
                    </p> -->
                    <p>
                        <small>
                            These eligibility criteria are threshold requirements for this service to appear in LHO search results.
                        </small>
                    </p>
                    <p>
                        <small>
                            If none are selected, the service will always appear in results for the legal matter and location selected on the previous screen.
                        </small>
                    </p>
                    <p>
                        <small>
                            If one or more are selected, the service will only appear if the user checks any one or more of those in their search.
                        </small>
                    </p>
                    <p class="margin-bottom-0">
                        <small>
                            For example:
                        </small>
                        <ul>
                            <li>
                                <small>
                                    if a service is only for women, that eligibility requirement should be set.
                                </small>
                            </li>
                            <li>
                                <small>
                                    If a service is only for women experiencing family violence, that eligibility requirement should be set as a single requirement, and not two separate requirements.
                                </small>
                            </li>
                        </ul>
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
                    @input="$parent.changeInForm"
                    ></multiselect>
                </div>
            </div>
            <!-- End: Eligibility Questions -->


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
            }
        },
        data () {
            return {
                selected_questions: [], //Those are vulnerability/eligibility questions
                selected_operator: [],
                initial_client_matters: {}
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
            set_initial_client_matters() {
                this.initial_client_matters = Object.assign({}, this.get_client_matters());
            },
            get_client_matters(){
                return {
                    sv_id: this.current_service.ServiceId,
                    vulnerability: this.selected_questions.map(item => item.QuestionId)
                };
            },
            save_client_matters() {
                $('#contentLoading').modal('show');
                let client_matters = this.get_client_matters();
                let url = '/service/client_eligibility';
                this.$parent.submit('post',url, client_matters)
                    .then(response => {
                        this.$parent.voidChangeInForm();
                        $('#contentLoading').modal('hide');
                        this.$parent.swal_messages(response.success, response.message);
                        this.initial_client_matters = Object.assign({}, this.get_client_matters());
                    })
                    .catch(error => {
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
            this.set_initial_client_matters();
            this.event_on_change_tab();
        },
    }
</script>