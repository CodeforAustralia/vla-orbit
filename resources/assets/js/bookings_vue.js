import "@babel/polyfill";
import Vue from 'vue';
import axios from 'axios';
import moment from 'moment';
import VueMce from 'vue-mce';

Vue.use(VueMce);

const config = {
    theme: 'modern',
    menubar:false,
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
    el: '#app',

    data: {
        config,
        actions: [],
        available_times: [],
        booking_availability: [],
        booking_bug_id: 0,
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
        description_value : '',
        template_flieds: [],
        header_value : '',
        disable_submit: false,
        display_help:false,
        display_no_book_refer: false,
        user: {}
    },

    methods: {
        onChangeSP: function (e) {
            var self = this;
            self.showLoading();
            self.display_no_book_refer = false;
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
            self.display_no_book_refer = false;
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
        setEReferralTemplate: function (e_ref_obj) {
            var self = this;
            let fields = e_ref_obj.Fields.split(',');
            self.template_flieds = fields;
            self.description_value = e_ref_obj.Body;
            self.header_value = e_ref_obj.Header;
        },
        getEreferralTemplate: function (erf_id) {
            var self = this;
            self.showLoading();
            axios.get('/e_referral/list_by_id/' + erf_id)
                .then(function (response) {
                    self.setEReferralTemplate(response.data);
                    self.hideLoading();
                })
                .catch(function (error) {
                    console.log(error);
                    self.hideLoading();
                });
        },
        onChangeFormType: function (e) {
            var self = this;
            document.getElementById("submit-booking").innerText = "Send e-Referral";
            self.disable_submit = false;
            self.is_direct_booking = false;
            if (parseInt(e.srcElement.value) === 0) { //Direct Booking
                self.is_direct_booking = true;
                document.getElementById("submit-booking").innerText = "Make booking";
                //if direct booking and has booking questions.
                if(self.can_book && self.current_service.ServiceBookingQuestions.length >= 1) {
                    self.validateQuestions();
                }

            } else if (parseInt(e.srcElement.value) > 0) {
                self.getEreferralTemplate(parseInt(e.srcElement.value));
            }

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
        checkBook: function () {
            var self = this;
            self.setBookingBugId(); //Check if is complex or needs interpreter
            let user_sp_id = document.getElementsByClassName('sp_id')[0].id;
            //self.can_book = parseInt(self.current_service.ServiceProviderId) === parseInt(user_sp_id);
            let len = self.actions.length;
            if(self.current_service.BookingServiceId && (self.current_service.ServiceProviderId) === parseInt(user_sp_id)){
                self.can_book = true;
            }
            for (let i = 0; i < len; i++) {
                if (self.current_service.BookingServiceId && (self.actions[i].Action === 'ALL' || self.actions[i].Action === 'BOOK')) {
                    self.can_book = true;
                }
                if (self.e_referral_forms && self.e_referral_forms.length > 0 && (self.actions[i].Action === 'ALL' || self.actions[i].Action === 'E_REFER')) {
                    self.can_e_referr = true;
                }
            }

            if(!self.can_book && !self.can_e_referr) {
                self.display_no_book_refer = true;
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
                url: "/service_provider/list_bookable_formated",
                data: {
                    sp_id: self.user.sp_id
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
            if((!self.interpreter_required && !self.is_complex) && self.dates_regular.length > 0) {
                days = Object.entries(self.booking_availability.regular);
            }
            if((self.interpreter_required || self.is_complex) && self.dates_interpreter.length > 0) {
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

        },
        updateServiceAvailability: function () {
            var self = this;
            self.checkBook(); //Depends on actions inside service
            if (self.can_book) {
                let booking_info = {
                    year: new Date().getFullYear(),
                    month: new Date().getMonth() + 1,
                    sv_id: self.booking_bug_id
                };
                self.getServiceAvailabilityByDate(booking_info);
            }
        },
        initGetUserDetails: function () {
            const self = this;
            let url = '/user/information';
            axios.get(url)
                .then(function (response) {
                    self.user = response.data;
                })
                .then(() => self.getServiceProviders())
                .catch(function (error) {
                    self.initGetUserDetails();
                });
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
                            if(!self.interpreter_required && !self.is_complex) {
                                return  self.dates_regular.includes(date_formated) ? true:false;
                            }
                            else if (self.interpreter_required || self.is_complex) {
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
        },
        displayField: function (field_name) {
            if (this.template_flieds.indexOf(field_name) >= 0) {
                return true;
            }
            return false;
        },
        validateQuestions: function() {
            let self = this;
            self.disable_submit = false;
            self.current_service.ServiceBookingQuestions.forEach(function(question) {
                if(question.QuestionType =='boolean') {
                    if(question.answer == null && question.QuestionValue.toLowerCase() == 'true') {
                        self.disable_submit =true;
                    }else if (question.answer == null && question.QuestionValue.toLowerCase() == 'false') {
                        //to do nothing
                    } else if ( String(question.answer) != question.QuestionValue.toLowerCase()) {
                        self.disable_submit = true;
                    }
                } else if(question.QuestionType =='numeric') {
                    if(question.Operator != 'in') {
                        question.answer = Number(question.answer);
                        question.QuestionValue = Number(question.QuestionValue);
                    }
                    if(!self.compareQuestions(question)) {
                        self.disable_submit = true;
                    }
                } else if(question.QuestionType =='text') {
                    if(!self.compareQuestions(question)) {
                        self.disable_submit = true;
                    }
                } else if(question.QuestionType =='multiple') {
                    if(question.options == null ) {
                        question.options = question.QuestionValue.split(',');
                    }
                    if(!self.compareQuestions(question)) {
                        self.disable_submit = true;
                    }
                }
            });
        },
        compareQuestions: function(args) {
            switch(args.Operator){
                case '=':
                    return  args.answer == args.QuestionValue;
                case '>':
                    return args.answer > args.QuestionValue;
                case '>=':
                    return args.answer >= args.QuestionValue;
                case '<':
                    return args.answer < args.QuestionValue;
                case '<=':
                    return args.answer <= args.QuestionValue;
                case '!=':
                    return args.answer != args.QuestionValue;
                case 'in':
                    let options = args.QuestionValue.split(',');
                    let booleanvalue = false
                    options.forEach(function(option){
                        if( args.answer != null && args.answer.trim().toLowerCase() == option.trim().toLowerCase()) {
                            booleanvalue = true;
                            return booleanvalue;
                        }
                    });
                    return booleanvalue;
                default:
                    return ( args.answer == " " || args.QuestionValue == " " );
            }
        }

    },

    mounted() {
        this.initGetUserDetails();
        this.initDatePicker();
        this.initDOBMask();
        this.initFormValidation();
        this.openBookingInReferral();
    }
});