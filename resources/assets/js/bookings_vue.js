import Vue from 'vue';
import 'babel-polyfill'
import moment from 'moment'
import axios from 'axios';

new Vue({
    el: '#app',

    data: {
        actions: [],
        available_times: [],
        booking_availability: [],
        booking_bug_id: 0,
        booking_template: '',
        booking_template_id: '',
        can_book: false,
        can_e_referr: false,
        client: {},
        current_service: [],
        e_referral_forms: [],
        interpreter_required: false,
        is_complex: false,
        is_direct_booking: false,
        selected_sp: document.getElementsByClassName('sp_id')[0].id,
        services: [],
        service_providers: [],
        user_sp_id: document.getElementsByClassName('sp_id')[0].id,
        dates_regular: [],
        dates_interpreter: [],
        hour:null,
    },

    methods: {
        onChangeSP: function (e) {

            var self = this;
            self.services = [];
            self.e_referral_forms = [];
            self.can_book = false;
            self.can_e_referr = false;
            self.available_times = [];
            self.getServicesPromise(e.srcElement.value)
                .then(services => {
                    self.hideLoading();
                })
                .catch(err => {
                    console.log(err);
                });
        },
        onChangeService: function (e) {

            var self = this;
            let services = self.services;
            let service_id = e.srcElement.value.split('-')[2];
            self.e_referral_forms = [];
            self.can_book = false;
            self.can_e_referr = false;
            self.available_times = [];
            let len = services.length;
            for (let i = 0; i < len; i++) {
                if (parseInt(services[i].ServiceId) === parseInt(service_id)) {
                    self.current_service = services[i];
                    self.actions = services[i].ServiceActions;
                    self.e_referral_forms = services[i].ReferralFormServices;
                    self.updateServiceAvailability();
                }
            }
            // Set the first option of the request_type select if only exist one.
            //
            setTimeout(onlyOne, 1000);
            function onlyOne() {
                let form_type = document.getElementById("request_type");
                if ( (self.e_referral_forms.length === 0 && self.can_book) ||  (self.e_referral_forms.length === 1 &&  !self.can_book) ) {
                    form_type.selectedIndex = "1";
                } else {
                    form_type.selectedIndex = "0";
                }
                var event = document.createEvent('Event');
                event.initEvent("change", false, true);
                form_type.dispatchEvent(event);
            };


        },
        onChangeFormType: function (e) {
            var self = this;
            let template = '';
            document.getElementById("submit-booking").innerText = "Send e-Referral";
            self.is_direct_booking = false;
            if (parseInt(e.srcElement.value) === 0) { //Direct Booking
                self.is_direct_booking = true;
                document.getElementById("submit-booking").innerText = "Make booking";
            } else if (parseInt(e.srcElement.value) === 1) { //'appointment_request'
                template += 'Brief outline of matter: <br> <br>';
                template += 'This call was supervised by (if relevant):  <br><br>';
            } else if (parseInt(e.srcElement.value) === 2) { //'for_assessment'
                template += 'Brief outline of matter: <br> <br>';
                template += 'Notes (special needs, urgency, time limits, tribunal/court hearing dates and location if the caller is in custody/detention):<br><br>';
                template += 'This call was supervised by (if relevant):  <br><br>';
            } else if (parseInt(e.srcElement.value) === 3) { //'phone_advice'
                template += 'Brief outline of matter: <br><br>';
                template += 'This call was supervised by (if relevant):  <br><br>';
            } else if (parseInt(e.srcElement.value) === 4) { //'duty_layer'
                template += 'Brief outline of matter: <br><br>';
                template += 'Court Date: <br><br>';
                template += 'This call was supervised by (if relevant):  <br><br>';
            } else if (parseInt(e.srcElement.value) === 5) { //'child_support'
                template += 'Brief outline of matter: <br><br>';
                template += 'Before completing this booking, ensure conflicts enquiry is completed. Please list names and DOB of other parties (including children): <br><br>';
                template += 'This call was supervised by (if relevant):  <br><br>';
            } else if (parseInt(e.srcElement.value) === 6) { //'child_protection'
                template += 'Urgent: Y/N <br><br><br>';
                template += '<strong>Conflict Check All Parties</strong> <br><br>';
                template += 'Mother: DOB,  ATSI Y/N, Conflict Y/N <br><br>';
                template += 'Mother\'s spouse/ domestic partner: DOB,  ATSI Y/N, Conflict Y/N <br><br>';
                template += 'Father: DOB,  ATSI Y/N, Conflict Y/N <br><br>';
                template += 'Father\'s spouse/domestic partner: DOB,  ATSI Y/N, Conflict Y/N <br><br>';
                template += 'Children (inc step): DOB,  ATSI Y/N, Conflict Y/N <br><br><hr>';
                template += 'Upcoming Court date: Y/N <br><br>';
                template += 'Court location: <br><br>';
                template += 'Are there orders in place? Y/N Details <br><br>';
                template += 'Date orders made? <br><br>';
                template += 'Upcoming appointment with DHHS?  Y/N  When? <br><br>';
                template += 'Has the client signed an agreement with DHHS in relation to the child? Y/N Details <br><br>';
                template += 'Are they asking the client to sign something? Details <br><br>';
                template += 'Has DHHS indicated they are thing of removing the child from the client\'s care or change the living arrangements? Details <br><br>';
                template += 'What has prompted the call to VLA today? Details <br><br>';
            }
            self.booking_template = template;
            self.booking_template_id = e.srcElement.value;
            $('#Desc').summernote('code', template);
            document.getElementsByName("service_provider_id")[0].value = self.selected_sp;
        },
        onChangeLanguage: function (e) {
            var self = this;
            self.interpreter_required = false;
            if (e.srcElement.value !== '') {
                self.interpreter_required = true;
            }
            self.setBookingBugId(); //Check if is complex or needs interpreter
            self.available_times = [];
            self.updateServiceAvailability();
        },
        onChangeComplex: function (e) {
            var self = this;
            self.is_complex = false;
            if (parseInt(e.srcElement.value) === 1) {
                self.is_complex = true;
            }
            self.setBookingBugId(); //Check if is complex or needs interpreter
            self.available_times = [];
            self.updateServiceAvailability();
        },
        canBook: function () {
            var self = this;
            self.setBookingBugId(); //Check if is complex or needs interpreter
            let user_sp_id = document.getElementsByClassName('sp_id')[0].id;
            self.can_book = parseInt(self.current_service.ServiceProviderId) === parseInt(user_sp_id);

            let len = self.actions.length;
            for (let i = 0; i < len; i++) {
                if (self.actions[i].Action === 'ALL' || self.actions[i].Action === 'BOOK') {
                    self.can_book = true;
                }
                if (self.actions[i].Action === 'ALL' || self.actions[i].Action === 'E_REFER') {
                    self.can_e_referr = true;
                }
            }
        },
        requireInterpreterOrComplex: function () {
            var self = this;
            return (self.is_complex || self.interpreter_required);
        },
        getServiceAvailabilityByDate: function (args) {

            var self = this;
            let year = args.year;
            let month = args.month;
            let sv_id = args.sv_id;
            if(sv_id && sv_id!==0){
                let dateInput = document.getElementById('booking-date');
                let initial_date = new Date();
                let last_day = new Date( year, month, 0);
                self.showLoading();
                axios.get('/booking/service/' +
                            sv_id +
                            '/getAvailability/' +
                            moment(initial_date).format('YYYY-MM-DD') +
                            "/" +
                            moment(last_day).format('YYYY-MM-DD'))
                    .then(function (response) {
                        self.dates_regular = Object.keys(response.data.regular);
                        self.dates_interpreter = Object.keys(response.data.interpreter);
                        self.booking_availability = response.data,
                        $(dateInput).prop('disabled', false);
                        $(dateInput).datepicker('setDate', year + "-" + month + "-01");
                        self.hideLoading();
                    })
                    .catch(function (error) {
                        self.booking_availability = [];
                        $(dateInput).prop('disabled', true);
                        self.hideLoading();
                    });
            }
        },
        getServicesPromise: function (sp_id) {
            var self = this;
            //Create a new promise
            return new Promise((resolve, reject) => {
                //Call get services by service provider
                $.ajax({
                    url: '/service/list_services_sp/' + sp_id,
                    method: 'GET',
                    success: function (data) {
                        self.services = data;
                        resolve(data); //send services back
                    },
                    error: function (error) {
                        reject(Error('Cannot load services'));
                    }
                });
            });
        },
        getServiceProviders: function () {

            var self = this;
            var csrf = document.getElementsByName("_token")[0].content;
            self.showLoading();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                method: "POST",
                url: "/service_provider/listFormated",
                data: {
                    scope: 'VLA'
                },
                success: function (data) {
                    self.service_providers = data;

                    self.getServicesPromise(self.user_sp_id)
                        .then(services => {
                            self.hideLoading();
                        })
                        .catch(err => {
                            console.log(err);
                        });
                },
                error: function (error) {
                    //alert('Please refresh the page.');
                    location.reload();
                    self.hideLoading();
                }
            });
        },
        setAvailability: function (selected_date) {
            var self = this;
            let date = selected_date;
            let times = [];
            self.available_times = [];
            let days = [];
            if(!self.interpreter_required && self.dates_regular.length > 0) {
                days = Object.entries(self.booking_availability.regular);
            }
            if(self.interpreter_required && self.dates_interpreter.length > 0) {
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
        setBookingBugId: function () {
            var self = this;
            self.booking_bug_id = self.current_service.BookingServiceId;
            if (self.requireInterpreterOrComplex()) {
                self.booking_bug_id = self.current_service.BookingInterpritterServiceId;
            }
        },
        updateServiceAvailability: function () {
            var self = this;
            self.canBook(); //Depends on actions inside service
            if (self.can_book) {
                let booking_info = {
                    year: new Date().getFullYear(),
                    month: new Date().getMonth() + 1,
                    sv_id: self.booking_bug_id
                };
                self.getServiceAvailabilityByDate(booking_info);
            }
        },
        initDatePicker: function () {

            var self = this;
            let dateInput = document.getElementById('booking-date');
            let current_date = new Date();
            $(dateInput).datepicker({
                    format: "yyyy-mm-dd",
                    startDate: current_date.toISOString().split('T')[0],
                    daysOfWeekDisabled: [0, 6],
                    todayHighlight: true,
                    beforeShowDay : function(date){
                        if(self.booking_bug_id && self.booking_bug_id !== 0){
                            let date_formated = moment(date).format('YYYY-MM-DD');
                            if(!self.interpreter_required) {
                                return  self.dates_regular.includes(date_formated) ? true:false;
                            }
                            else if (self.interpreter_required) {
                                return  self.dates_interpreter.includes(date_formated) ? true:false;
                            }
                        }
                    }
                })
                .on("changeDate", function (e) {

                    if (e.hasOwnProperty("date")) {
                        let selected_date = e.date.getFullYear() + "-" +
                                            ('0' + (e.date.getMonth() + 1)).slice(-2) + "-" +
                                            ('0' + e.date.getDate()).slice(-2);
                        /*{
                            day: ('0' + e.date.getDate()).slice(-2),
                            month: ('0' + (e.date.getMonth() + 1)).slice(-2),
                            year: e.date.getFullYear()
                        }*/
                        self.setAvailability(selected_date);
                    }
                })
                .on('changeMonth', function (e) {
                    self.setBookingBugId(); //Check if is complex or needs interpreter

                    let booking_info = {
                        year: e.date.getFullYear(),
                        month: e.date.getMonth() + 1,
                        sv_id: self.booking_bug_id
                    };
                    self.getServiceAvailabilityByDate(booking_info);
                });
        },
        initSummernote: function () {
            //TODO: implement with in a vue js way
            let description = document.getElementById('Desc');
            $(description).summernote({
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['link', ['linkDialogShow', 'unlink']]
                ]
            });
        },
        initDOBMask: function () {
            //TODO: implement with in a vue js way
            let dob = document.getElementById('dob');
            $(dob).inputmask("d/m/y", {
                "placeholder": "dd/mm/yyyy"
            });
        },
        initFormValidation: function () {

            var self = this;

            $("#bookingForm").validate({
                ignore: ":not(:visible)",
                submitHandler: function (form, event) {
                    event.preventDefault();

                    let email = self.current_service.Email;
                    let message_title = self.current_service.ServiceName + '\n' + self.current_service.ServiceProviderName;
                    let message_text = 'An email will be sent to ' + email + '\nA copy is bcc to the LegalInfoCallBack mailbox';
                    let button_text = 'Send e-Referral';

                    if (self.is_direct_booking) {
                        message_text = 'The office/program area providing the service will see the booking in Orbit';
                        button_text = 'Make booking';
                    }

                    swal({
                            title: message_title,
                            text: message_text,
                            confirmButtonText: button_text,
                            confirmButtonColor: "#26C281",
                            showCancelButton: true,
                            cancelButtonColor: "#d33"
                        },
                        function (confirm) {
                            if (confirm) {
                                document.getElementsByName("service_provider_id")[0].removeAttribute('disabled');
                                document.getElementsByName("ServiceId")[0].removeAttribute('disabled');
                                form.submit();
                                self.showLoading();
                            }
                        });
                }
            });
        },
        showLoading: function () {
            let contentLoading = document.getElementById('contentLoading');
            $(contentLoading).modal("show");
        },
        hideLoading: function () {
            let contentLoading = document.getElementById('contentLoading');
            $(contentLoading).modal("hide");
        },
        openBookingInReferral: function () {

            let open_bookings = document.getElementsByClassName("open-booking");

            for (let pos = 0; pos < open_bookings.length; pos++) {
                let open_booking = open_bookings[pos];
                open_booking.addEventListener('click', () => {

                    $("#contentLoading").modal("show");
                    const service_card = $(open_booking).closest(".card-container");
                    const sv_id = $(service_card).attr("id");
                    const booking_ids = $(open_booking).attr("id").split('-');
                    const sp_id = booking_ids[2];
                    const booking_id = booking_ids[0];
                    const booking_interpretor_id = booking_ids[1];

                    var self = this;
                    self.services = [];
                    self.current_service = [];
                    self.e_referral_forms = [];
                    self.can_book = false;
                    self.can_e_referr = false;
                    self.available_times = [];
                    self.selected_sp = sp_id;

                    self.getServicesPromise(sp_id)
                        .then(services => {
                            let len = self.services.length;
                            for (let i = 0; i < len; i++) {
                                if (parseInt(self.services[i].ServiceId) === parseInt(sv_id)) {
                                    self.current_service = self.services[i];
                                    self.actions = self.services[i].ServiceActions;
                                    self.e_referral_forms = self.services[i].ReferralFormServices;
                                    self.updateServiceAvailability();
                                }
                            }
                            setTimeout(() => {
                                document.getElementsByName("service_provider_id")[0].value = sp_id;
                                document.getElementsByName("request_type")[0].removeAttribute('disabled');
                                document.getElementsByName("service_provider_id")[0].setAttribute('disabled', 'disabled');
                                document.getElementsByName("ServiceId")[0].setAttribute('disabled', 'disabled');
                                document.getElementsByName("ServiceId")[0].value = booking_id + '-' + booking_interpretor_id + '-' + sv_id;
                                $("#contentLoading").modal("hide");
                            }, 1000);

                        })
                        .catch(err => {
                            console.log(err);
                        });
                });
            }
        }

    },

    mounted() {
        this.initSummernote();
        this.initDatePicker();
        this.initDOBMask();
        this.initFormValidation();
        this.getServiceProviders();
        this.openBookingInReferral();
    }
});