import Vue from 'vue';
//import service_general_settings from './components/service_self_serving/service_general_settings.vue'
import service_clients_matters from './components/service_self_serving/service_clients_matters.vue'


const service_management = new Vue({
    el: '#service_self_serving',
    components: {
//        'service-general-settings': service_general_settings,
        'service-clients-matters': service_clients_matters
    }
})