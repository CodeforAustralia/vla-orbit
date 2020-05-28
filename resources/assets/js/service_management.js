import "@babel/polyfill";
import Vue from 'vue';
import axios from 'axios';
import EventBus from './utils/event-bus';
import Multiselect from 'vue-multiselect';
import VueMce from 'vue-mce';
import service_general_settings from './components/service_self_serving/service_general_settings.vue';
import service_clients_matters from './components/service_self_serving/service_clients_matters.vue';
import service_intake_options from './components/service_self_serving/service_intake_options.vue';
import service_legal_matters from './components/service_self_serving/service_legal_matters.vue';
import service_client_history from './components/service_self_serving/service_client_history.vue';
import VueSweetalert2 from 'vue-sweetalert2';
import ToggleButton from 'vue-js-toggle-button'

Vue.use(VueSweetalert2);
Vue.component('multiselect', Multiselect);
Vue.use(VueMce);
Vue.use(ToggleButton)

const service_management = new Vue({
    el: '#service_self_serving',
    components: {
        'service-general-settings': service_general_settings,
        'service-clients-matters': service_clients_matters,
        'service-intake-options': service_intake_options,
        'service-legal-matters': service_legal_matters,
        'service-client-history': service_client_history
    },
    data: {
        tab_active: 'settings',
        current_path: window.location.pathname,
        modified: false
    },
    computed: {
        is_new_service() {
            return this.current_path === '/service/new';
        }
    },
    methods: {
        save_service_first() {
            let self = this;
            self.$swal('Please save this service first.', '', 'error');
        },
        change_tab(tab_name) {
            let self = this;
            if (self.tab_active !== tab_name) {
                //Confirm if you want to save changes when changing a tab
                EventBus.$emit('CHANGE_TAB_' + self.tab_active.toUpperCase(), self.tab_active);
                self.tab_active = tab_name;
            }
        },
        submit(requestType, url, data) {
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
        changeInForm() {
            this.modified = true;
            console.log("Change in form");
        },
        voidChangeInForm() {
            this.modified = false;
            console.log("Void Change in form");
        },
        avoidLeaveWithoutChanges: function () {
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
        swal_messages(type, message) {
            this.$swal(type.charAt(0).toUpperCase() + type.slice(1), message, type);
        },
    },
    mounted() {
        this.avoidLeaveWithoutChanges();
    },
})