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

        </div>
    </div>
</template>

<script>

    import Vue from 'vue';
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
            }
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
            }
        },
        created() {
            this.pre_select_questions();
        },
    }
</script>