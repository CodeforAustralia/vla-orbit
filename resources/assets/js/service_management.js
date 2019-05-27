import Vue from 'vue';
import service_general_settings from './components/service_self_serving/service_general_settings.vue'


const service_management = new Vue({
    el: '#service_self_serving',
    components: {
        'service-general-settings':service_general_settings
    }
})