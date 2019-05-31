<template>
    <div class="row">
        <div class="col-xs-12">
            <h4>Service client matters</h4>

            <p><small>These eligibility criteria are threshold requirements for entry into the service. The chosen criteria are service wide and will be overridden is a condition is added. If needed <a data-toggle="modal" href="#request-vulnerability">request a new criteria</a>.</small></p>
            <div class="form-group">
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

            <h4>Booking Questions - if booking is enable will be here</h4>

            <button type="button" class="btn btn-circle green margin-top-15" @click="save_client_matters()">Save Client Matters</button>

        </div>
    </div>
</template>

<script>

    import Vue from 'vue';
    import axios from 'axios';
    import Multiselect from 'vue-multiselect';
    Vue.component('multiselect', Multiselect);

	export default {
        props: {
            eligibility_questions: {
                required: true,
                type: Array
            },
            selected_eligibility_questions: {
                type: Array,
                default: []
            },
            current_service: {
                default: {}
            },
        },
        data () {
            return {
                selected_questions: []
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
            save_client_matters() {
                $('#contentLoading').modal('show');
                let client_matters = {
                    sv_id: this.current_service.ServiceId,
                    vulnerability: this.selected_questions.map(item => item.QuestionId),
                    booking_question: []
                };
                let url = '/service/client_eligibility';
                this.submit_service_cm('post',url, client_matters)
                .then(response => {
                    $('#contentLoading').modal('hide');
                    this.swal_messages(response.success, response.message);
                })
                .catch(error => {
                    console.log(error);
                });
            },
            /**
             * Submit Client Matters information
             * @param {string} requestType post, get, patch, update
             * @param {string} url End point to submit from
             * @param {data} object with information of client matters
             */
            submit_service_cm(requestType, url, data) {
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
                swal(type.charAt(0).toUpperCase() + type.slice(1), message, type);
            }
        },
        created() {
            this.pre_select_questions();
        },
    }
</script>