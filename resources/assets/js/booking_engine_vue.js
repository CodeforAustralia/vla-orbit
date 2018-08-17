require('./bootstrap');

Vue.component('service-days', require('./components/booking_engine/days/service_days.vue'));
Vue.component('service-hours', require('./components/booking_engine/hours/service_hours.vue'));
Vue.component('service-adhoc', require('./components/booking_engine/adhoc/service_adhoc.vue'));

new Vue({
    el: '#booking_engine',

    data: {
        sv_id: 0,
    },
    methods: {
        openCalendar(id) {
            var self = this;
            self.sv_id = id;
            $('#set_days').modal('show');
        },
        openSchedule(id) {
            var self = this;
            self.sv_id = id;
            $('#set_hours').modal('show');
        },
        openAdhoc(id) {
            var self = this;
            self.sv_id = id;
            $('#set_adhoc_booking').modal('show');
        },
        initReadmore() {
            $('.description').readmore({
                collapsedHeight: 56,
                speed: 1000,
                lessLink: '<a href="#">Read less</a>'
            });
        }
    },
    mounted() {
        this.initReadmore();
    },
});
