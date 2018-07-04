import Vue from 'vue';
import Form from './form';

new Vue({

    el: '#request_section',

    data: {
        parent_matters: {},
        question_groups: {},
        matter_form: new Form({
            name: 'matter',
            matter_name: '',
            parent_matter: '',
            reason: ''
        }),
        vulnerability_form: new Form({
            name: 'criteria',
            criteria_name: '',
            criteria_group: '',
            reason: ''
        }),
    },

    methods: {
        loadQuestionGroupPromise: function () {
            return new Promise((resolve, reject) => {
                $.get("/question_group/list", function (data) {
                    resolve(data.data);
                });
            });
        },
        loadQuestionGroup: function () {
            let self = this;
            self.loadQuestionGroupPromise()
            .then(data => {
                self.question_groups = data;
            });
        },
        loadParentMattersPromise: function () {
            return new Promise((resolve, reject) => {
                $.get("/matter/listFormated", function (data) {
                    let output = [];
                    let group_matters = [];
                    for (let index = 0; index < data.length; index++) {
                        let info = data[index].text.split('-');
                        let text = info[0] + '-' + info[1];
                        if (output.indexOf(text) < 0) {
                            output.push(text);
                            group_matters.push({
                                id: text.trim(),
                                text: text.trim()
                            });
                        }
                    }
                    resolve(group_matters);
                });
            });
        },
        loadParentMatters: function () {
            let self = this;
            self.loadParentMattersPromise()
                .then(group_matters => {
                    self.parent_matters = group_matters;
                });
        },
        /**
         * On Request Matter
         */
        request_matter: function (e) {
            let self = this;
            if (self.validate(self.matter_form)) {
                self.matter_form.submit('post', '/service/request_addition')
                    .then(() => {
                        swal("Thank you!", "We’ll look at your request and get back to you when it is added or if we have any questions.", "success ");
                        $('#request-matter').modal('hide');
                    }).catch(() => {
                        console.log('Error in Server')
                    });
            }
        },
        /**
         * On Request Vulberability
         */
        request_vulnerability: function (e) {
            let self = this;
            if (self.validate(self.vulnerability_form)) {
                this.vulnerability_form.submit('post', '/service/request_addition')
                    .then(() => {
                        swal("Thank you!", "We’ll look at your request and get back to you when it is added or if we have any questions.", "success ");
                        $('#request-vulnerability').modal('hide');
                    }).catch(() => {
                        console.log('Error in Server')
                    });
            }
        },
        validate: function(form_to_validate) {
            let valid = true;
            let object_form = form_to_validate;
            let error_msg = {};
            for (let propety in object_form) {
                if (object_form[propety] === '') {
                    error_msg[propety] = ['This field is required'];
                    object_form.errors.record(error_msg);
                    valid = false;
                } else {
                    object_form.errors.clear(propety);
                }
            }
            return valid;
        }
    },
    mounted() {
        let self = this;
        self.loadQuestionGroup();
        self.loadParentMatters();
    }
});