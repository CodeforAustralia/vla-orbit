import Vue from 'vue';
import EventBus from './utils/event-bus';
//import service_general_settings from './components/service_self_serving/service_general_settings.vue'
import service_clients_matters from './components/service_self_serving/service_clients_matters.vue';
import service_intake_options from './components/service_self_serving/service_intake_options.vue';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.use(VueSweetalert2);

const service_management = new Vue({
    el: '#service_self_serving',
    components: {
//        'service-general-settings': service_general_settings,
        'service-clients-matters': service_clients_matters,
        'service-intake-options': service_intake_options
    },
    data: {
        tab_active: 'settings'
    },
    methods: {
        change_tab(tab_name) {
            let self = this;
            if (self.tab_active !== tab_name) {
                //Confirm if you want to save changes when changing a tab
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
                        EventBus.$emit('CHANGE_TAB_' + self.tab_active.toUpperCase(), self.tab_active);
                    }
                    self.tab_active = tab_name;
                });
            }
        }
    },
})