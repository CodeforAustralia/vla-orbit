import Vue from 'vue';
import Multiselect from 'vue-multiselect';
import axios from 'axios';
import * as uiv from 'uiv';
import moment from 'moment'
import FullCalendar from 'vue-full-calendar';
import VueMce from 'vue-mce';
import EventBus from './utils/event-bus';
import Calendar from './components/calendar/calendar.vue';
import UserBookingDatatable from './components/datatable/user_booking_datatable.vue';

Vue.use(uiv);
Vue.use(FullCalendar);
Vue.use(VueMce);
Vue.component('multiselect', Multiselect);
Vue.component('Calendar', Calendar);
Vue.component('user_booking_datatable', UserBookingDatatable);

const config = {
    theme: 'modern',
    menubar: false,
    fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px",
    plugins: 'lists paste',
    paste_as_text: true,
    toolbar1: 'formatselect fontsizeselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css'
    ],
};

new Vue({
    el: '#sp-calendar',

    data: {
        config,
        current_booking: {},
        service_provider_options: [],
        booking_status_options : [],
        service_provider_selected : {},
        service_provider_id: 0,
        temp_value : '',
        comment_value : '',
        edit_field : '',
        show_date: false,
        show_sms: false,
        dates_regular: [],
        dates_interpreter: [],
        booking_availability: [],
        available_times:[],
        hour:null,
        selected_date:null,
        sms:null,
        booking_to_delete : 0,
        booking_to_update : 0

    },
    methods: {
        intitServiceProviders: function () {
            $("#contentLoading").modal("show");
            const self = this;
            let url = '/service_provider/listFormated';
            let scope = {
                scope: 'VLA'
            };
            axios.post(url, scope)
                .then(function (response) {
                    self.service_provider_options = response.data;
                    $("#contentLoading").modal("hide");
                })
                .then(() => self.selectInitialServiceProvider())
                .catch(function (error) {
                    alert('Please refresh the page');
                    $("#contentLoading").modal("hide");
                });
        },
        selectInitialServiceProvider: function() {
            const self = this;
            let sp_id = parseInt(document.querySelector('.sp_id').id);

            self.service_provider_id = sp_id;
            for (let index = 0; index < self.service_provider_options.length; index++) {
                if (sp_id == self.service_provider_options[index].id) {
                    self.service_provider_selected = self.service_provider_options[index];
                }
            }
        },
        getServiceProviderBookings: function (service_provider) {
            const self = this;
            self.service_provider_id = service_provider.id;
        },
        enableEditing: function(value, name){
            const self = this;
            self.temp_value = value;
            if(name === 'comment') {
                self.comment_value = value;
            }
            self.edit_field = name;
        },
        showField : function(name) {
            const self = this;
            return self.edit_field == name
        },
        disableEditing: function(){
            const self = this;
            self.temp_value = '';
            self.comment_value = '';
            self.edit_field = null;
            self.show_date = false;
            self.show_sms = false;
        },
        updateBookingField: function (field) {
            $("#contentLoading").modal("show");
            const self =this;
            let url = '/booking';
            let temp_field = '';
            if (field === 'comment') {
                temp_field = self.current_booking[field]; // save it in case of error
                self.current_booking[field] = self.comment_value;
            } else if (field === 'booking_status_id') {
                self.current_booking.booking_status_id = self.current_booking.bookingstatus ?
                                                        self.current_booking.bookingstatus.id : null;
            } else {
                let fields = field.split('.');
                temp_field = self.current_booking[fields[0]][fields[1]]; //save it in case of error
                self.current_booking[fields[0]][fields[1]] = self.temp_value;
                if (field === 'client.contact') {
                    self.current_booking.client.contact = Number(self.temp_value.replace(/[^0-9]+/g, ""));
                }
            }
            axios.patch(url, self.current_booking)
                .then(function (response) {
                    if(response.data.error) {
                        alert(Object.values(response.data.error));
                        if (field === 'comment') {
                            self.current_booking[field] = temp_field;
                            self.comment_value = temp_field;
                        } else {
                            self.current_booking[fields[0]][fields[1]] = temp_field;
                            self.temp_value = temp_field;
                        }
                    }
                    else {
                        self.booking_to_update = self.current_booking.id;
                        EventBus.$emit('update_booking', field, self.current_booking);
                    }
                    $("#contentLoading").modal("hide");
                })
                .catch(function (error) {
                    console.log(error)
                    $("#contentLoading").modal("hide");
                });
            this.disableEditing();
        },
        updateBookingDate: function (){
            $("#contentLoading").modal("show");
            const self =this;
            let url = '/booking';
            let temp_booking = self.current_booking; // Save old values in case of error.
            if(!self.hour || !self.selected_date) {
                alert("Please select a Date and Time");
                $("#contentLoading").modal("hide");
            } else {
                self.current_booking.date = self.selected_date;
                self.current_booking.resource_id = self.hour.resource_id;
                self.current_booking.start_hour = self.hour.start_time;
                self.current_booking.time_length = self.hour.duration;
                self.current_booking.booking_time = moment(self.selected_date).add(parseInt(self.hour.start_time), 'm').format('h:mm A');
                axios.patch(url, self.current_booking)
                    .then(function (response) {
                        if(response.data.error) {
                            alert(Object.values(response.data.error));
                            self.current_booking = temp_booking;
                        }
                        else{
                            self.show_date = false;
                            self.booking_to_update = self.current_booking.id;
                        }
                        $("#contentLoading").modal("hide");
                    })
                    .catch(function (error) {
                        console.log(error)
                        $("#contentLoading").modal("hide");
                    });
            }
        },
        getBookingAvailability: function (args) {
            const self = this;
            let dateInput = document.getElementById('booking-date');
            let year = args.year;
            let month = args.month;
            let initial_date = new Date();
            let last_day = new Date( year, month, 0);
            $("#contentLoading").modal("show");
            axios.get('/booking/service/' +
                        self.current_booking.service_id +
                        '/getAvailability/' +
                        moment(initial_date).format('YYYY-MM-DD') +
                        "/" +
                        moment(last_day).format('YYYY-MM-DD'))
                .then(function (response) {
                    self.dates_regular = Object.keys(response.data.regular);
                    self.dates_interpreter = Object.keys(response.data.interpreter);
                    self.booking_availability = response.data,
                    $(dateInput).datepicker('setDate', year + "-" + month + "-01");
                    $("#contentLoading").modal("hide");
                })
                .catch(function (error) {
                    self.booking_availability = [];
                    $("#contentLoading").modal("hide");
                });
        },
        initDatePicker: function () {
            const self = this;
            self.show_date = true;
            self.available_times = [];
            self.hour=null;
            let dateInput = document.getElementById('booking-date');
            let current_date = new Date();
            let booking_info = {
                year: new Date().getFullYear(),
                month: new Date().getMonth() + 1,
            };
            self.getBookingAvailability(booking_info);
            $(dateInput).datepicker({
                format: "yyyy-mm-dd",
                startDate: current_date.toISOString().split('T')[0],
                daysOfWeekDisabled: [0, 6],
                todayHighlight: true,
                autoclose: true,
                container: '#bookingInfo',
                beforeShowDay : function(date){
                    let date_formated = moment(date).format('YYYY-MM-DD');
                    if(self.current_booking.is_interpreter == 0 ) {
                        return (self.dates_regular.indexOf(date_formated) >= 0 ? true : false);
                        // return  self.dates_regular.includes(date_formated) ? true:false;
                    }
                    else if (self.current_booking.is_interpreter == 1 ) {
                        return (self.dates_interpreter.indexOf(date_formated) >= 0 ? true : false);
                        // return  self.dates_interpreter.includes(date_formated) ? true:false;
                    }

                }})
                .on("changeDate", function (e) {

                    if (e.hasOwnProperty("date")) {
                        let selected_date = e.date.getFullYear() + "-" +
                                            ('0' + (e.date.getMonth() + 1)).slice(-2) + "-" +
                                            ('0' + e.date.getDate()).slice(-2);
                        self.selected_date = selected_date;
                        self.setAvailableTimes(selected_date);
                    }
                })
                .on('changeMonth', function (e) {

                    booking_info = {
                        year: e.date.getFullYear(),
                        month: e.date.getMonth() + 1
                    };
                    self.getBookingAvailability(booking_info);
                });
                self.getBookingAvailability(booking_info);
        },
        setAvailableTimes: function (selected_date) {
            const self = this;
            let date = selected_date;
            let times = [];
            self.available_times = [];
            let days = [];
            if(self.current_booking.is_interpreter == 0  && self.dates_regular.length > 0) {
                days = Object.entries(self.booking_availability.regular);
            }
            if(self.current_booking.is_interpreter == 1  && self.dates_interpreter.length > 0) {
                days = Object.entries(self.booking_availability.interpreter);
            }
            if(days.length > 0) {
                days.forEach(function(day) {
                    if(date === day[0]) {
                        let time = Object.values(day[1]).slice(0);
                        time.forEach(function(hour){
                        if (Array.isArray(hour)) {
                            var first = function(element) { return !!element };
                            times.push(hour.find(first));
                        }
                        else {
                            let time_data = Object.values(hour);
                            times.push(time_data[0]);
                        }
                        });
                    }
                });
            }
            self.available_times = times;
        },
        getCurrentServiceTemplate: function () {
            const self = this;
            $("#contentLoading").modal("show");
            let url = '/sms_template/getTemplateByServiceBookingId';
            let booking = {
                FirstName: self.current_booking.client.first_name,
                Mobile : self.current_booking.client.contact,
                BookingDate : self.current_booking.date,
                BookingTime : self.current_booking.booking_time
            };

            axios.get(url , {
                params: {
                    sv_id: self.current_booking.orbit_service.ServiceId,
                    booking: booking
                }
            })
            .then(function (response) {
                self.sms = response.data.replace(/\s\s+/g, ' ').trim();
                self.show_sms = true;

                $("#contentLoading").modal("hide");
            })
            .catch(function (error) {
                $("#contentLoading").modal("hide");
                self.show_sms = true;
            });
        },
        sendSMSReminder : function() {
            const self = this;
            $("#contentLoading").modal("show");
            let url = "/booking/sendSmsReminder";
            let booking = {
                Mobile: self.current_booking.client.contact,
                FirstName: self.current_booking.client.first_name ,
                BookingDate: self.current_booking.date,
                BookingTime: self.current_booking.booking_time,
                ServiceId: self.current_booking.service_id,
                RefNo: self.current_booking.id,
                template : self.sms,
                IsSafeSMS : 1,
            };
            axios.get(url , {
                params: {
                    booking: booking
                }
            })
            .then(function (response) {
                if(self.current_booking.sms_date_hour) {
                    self.current_booking.sms_date_hour += "\n" + moment().format('DD-MM-YYYY h:mm A');
                }
                else {
                    self.current_booking.sms_date_hour = moment().format('DD-MM-YYYY h:mm A');
                }
                self.show_sms = false;
                $("#contentLoading").modal("hide");
            })
            .catch(function (error) {
                $("#contentLoading").modal("hide");
                self.show_sms = true;
            });
        },
        deleteBooking : function() {
            const self = this;
            let confirmation = confirm("Are you sure that you want to delete it?\n To confirm press OK or Cancel.");
            if(confirmation == true) {
                $("#contentLoading").modal("show");
                let url = '/booking/' + self.current_booking.id;
                axios.delete(url)
                .then(function (response) {
                    let bo_id = self.current_booking.id;
                    self.booking_to_delete = bo_id;
                    self.current_booking = {};
                    EventBus.$emit('delete_booking', bo_id);
                    $("#bookingInfo").modal("hide");
                    $("#contentLoading").modal("hide");
                })
                .catch(function (error) {
                    alert('Error deleting event, please refresh the page');
                    $("#contentLoading").modal("hide");
                });
            }
        },

    },
    mounted() {
        this.intitServiceProviders();
    }

});