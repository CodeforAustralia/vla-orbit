import Vue from 'vue';
import Multiselect from 'vue-multiselect';
import axios from 'axios';
import * as uiv from 'uiv';
import moment from 'moment'
import FullCalendar from 'vue-full-calendar';

Vue.use(uiv);
Vue.use(FullCalendar);
Vue.component('multiselect', Multiselect);
Vue.component('Calendar', require('./components/calendar/calendar.vue'));

new Vue({
    el: '#sp-calendar',

    data: {
        current_booking: {},
        service_provider_options: [],
        service_provider_selected: [],
        service_provider_id: 0
    },
    methods: {
        intitServiceProviders: function () {
            $("#contentLoading").modal("show");
            var self = this;
            let url = '/service_provider/list';
            axios.get(url)
                .then(function (response) {
                    console.log(response);
                    self.service_provider_options = response.data.data;
                    $("#contentLoading").modal("hide");
                })
                .catch(function (error) {
                    console.log(error);
                    alert('Please refresh the page');
                    $("#contentLoading").modal("hide");
                });
        },
        getServiceProviderBookings: function (service_provider) {
            var self = this;
            self.service_provider_id = service_provider.ServiceProviderId;
        },
    },
    mounted() {
        this.intitServiceProviders();
    }

});