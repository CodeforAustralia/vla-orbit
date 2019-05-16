<template>
    <div>
        <div class="col-sm-3 col-xs-12 filter_container">
            <h2 class="filters_title">Filters</h2>
            <h5 class="filters_subtitle">Filter by service</h5>
            <ul class="list-unstyled">
                <li v-for="service in services" v-bind:key="service.BookingServiceId" class="filters_text">
                    <label :for="service.BookingServiceId">
                        <input type="checkbox" :value="service.BookingServiceId" v-model="selectedServices">
                        {{ service.ServiceName }}
                        <i class="fa fa-circle" :style="getServiceColor(service.BookingServiceId)"></i>
                    </label>
                </li>
            </ul>
            <a href="javascript:;" class="btn btn-sm col-xs-12 btn-default main-green" @click="displayAvailability()">
                {{ availability_button_text }}
            </a>
        </div>
        <div class="col-sm-9 col-xs-12">
            <full-calendar
            :config="config"
            :events="events"
            @event-selected="eventSelected"
            @event-render="eventRendered"
            />
        </div>
    </div>
</template>

<script>
	import moment from 'moment'
    import axios from 'axios';
    import EventBus from '../../utils/event-bus';
    import { getOptimalContrastText } from '../../utils/contrast_color';

	export default {
        props: [
                'service_provider_id',
                'current_booking',
                'booking_to_delete',
                'booking_status_options',
                'booking_to_update'
                ],
        data () {
            let self = this;
            return {
                    availability_button_text: 'Show available slots',
                    services: {},
                    service_colors: {},
                    services_availability: {},
                    unavailable: {},
                    events: [],
                    initial_events: [],
                    selectedServices: [], //If empty should display all
                    display_free_slots: false,
                    config: {
                        header: {
                            left: 'title',
                            center: '',
                            right: 'prev,next today,month,agendaWeek,agendaDay, availabilityButton'
                        },
                        defaultView: 'month',
                        views: {
                            agendaWeek:{
                                columnHeaderFormat:'ddd D/M',
                                slotEventOverlap: false
                            }
                        },
                        weekends: false,
                        editable: false,
                        droppable: false, // this allows things to be dropped onto the calendar !!!
                        businessHours: {
                            start: '7:00', // a start time (7 am in this example)
                            end: '19:00', // an end time (7 pm in this example)
                        },
                        eventSources: [{
                            url: '/booking/service_provider/booking',
                            type: 'GET',
                            data:  function() { // a function that returns an object
                                    return {
                                        sp_id: self.service_provider_id
                                    };
                            },
                            beforeSend: function () {
                                $("#contentLoading").modal('show');
                            },
                            error: function(error) {
                                $("#contentLoading").modal('hide');
                            },
                            success: function (response) {
                                self.initCalendar(response.bookings)
                                .then(() => {
                                    self.initial_events = [...self.events];
                                    self.services = response.services;
                                    //self.checkShowAvailability();
                                    self.filterEvents();
                                    $("#contentLoading").modal('hide');
                                }).catch(err => {
                                    console.log(err);
                                });
                            }
                        }]
                    },
            }
        },
        methods: {
            displayAvailability: function () {
                const self = this;
                if(self.display_free_slots) {
                    self.availability_button_text = 'Show available slots';
                    self.display_free_slots = false;
                } else {
                    self.availability_button_text = 'Hide available slots';
                    self.display_free_slots = true;
                }
                self.filterEvents();
            },
            hideFreeSlots: function (){
                const self = this;
                self.events = self.events.filter(event => !event.free_slot );
            },
            showFreeSlots: function () {
                const self = this;
                for (const service_id in this.services_availability) {
                    if(self.selectedServices.indexOf(service_id) >= 0 || self.selectedServices.length === 0) {
                        const service_info = this.services_availability[service_id];
                        if (service_info.hasOwnProperty('regular')) {
                            self.iterateAppointmentsByServiceId(service_info, 'regular', service_id);
                        }
                        if (service_info.hasOwnProperty('interpreter')) {
                            self.iterateAppointmentsByServiceId(service_info, 'interpreter', service_id);
                        }
                    }
                }
            },
            iterateAppointmentsByServiceId: function (service_info, type, service_id) {
                const self = this;
                for (const date_appt in service_info[type]) {
                    const available_slots = service_info[type][date_appt];
                    for (const free_slot in available_slots) {
                        self.service_colors[service_id] = service_info.service_info.color;
                        const slot = available_slots[free_slot][0];
                        let svc = self.services.filter((service) => service.BookingServiceId == service_id );
                        let int_icon = (type === 'interpreter' ? '<i class="fa fa-globe"></i> ' : '');
                        let sv_name =  (( svc[0].ServiceName.length > 12 ) ? svc[0].ServiceName.slice(0,10) + '...' : svc[0].ServiceName)
                        let cal_event = {
                                title: `${int_icon}Available - ${sv_name}`,
                                start: moment(date_appt).add(parseInt(parseInt(slot.start_time)), 'm'),
                                end: moment(date_appt).add(parseInt(parseInt(slot.start_time)) + parseInt(slot.duration), 'm'),
                                color: '#fff',
                                textColor: '#000',
                                borderColor: service_info.service_info.color,
                                className: 'free-slot',
                                sv_id: svc[0].BookingServiceId,
                                free_slot: true,
                                editable: false
                        };
                        self.events.push(cal_event);
                    }
                }
            },
            filterServices: function() {
                const self = this;
                self.events = self.initial_events.filter(event => {
                    let is_present = false;
                    self.selectedServices.forEach(id => {
                        if(event.sv_id == id) {
                            is_present = true;
                        }
                    });
                    return is_present;
                });
            },
            filterEvents: function(){
                const self = this;
                if(self.selectedServices.length < 1){ //Show all events
                    self.events = self.initial_events;
                } else { //Filter by services
                    self.filterServices();
                }
                if(self.display_free_slots){ //Show free slots
                    self.showFreeSlots();
                } else { //Hide free slots
                    self.hideFreeSlots();
                }
            },
            getEventsBySelectedSP: function() {
                let self = this;
                let today = moment().format('YYYY-MM-01');
                let in_a_month = moment().add(1, 'month').format('YYYY-MM-10');
                let url = '/booking/service_provider/booking/?start=' + today + '&end=' + in_a_month + '&sp_id=' + self.service_provider_id;

                $("#contentLoading").modal("show");
                if(self.service_provider_id > 0){
                    axios.get(url)
                        .then(function (response) {
                            self.initCalendar(response.data.bookings);
                            return response;
                        })
                        .then((response) => {
                            self.initial_events = [...self.events];
                            self.services = response.data.services;
                            self.services_availability = response.data.services_availability;
                            self.$emit('update:booking_status_options', response.data.booking_status);
                        })
                        .then(() => {
                            self.selectedServices = []; //Reset list of selected services
                            self.filterEvents();
                            $("#contentLoading").modal("hide");
                            $(".modal-backdrop").remove();
                        })
                        .catch(function (error) {
                            self.getEventsBySelectedSP();
                            $("#contentLoading").modal("hide");
                            $(".modal-backdrop").remove();
                        });
                }
            },
            getServiceColor: function (id){
                if(this.service_colors.hasOwnProperty(id)){
                    return 'color: ' + this.service_colors[id];
                } else {
                    return 'display:none';
                }
            },
            eventRendered(event, element, view){
                if(event.hasOwnProperty('booking')) {
                    let slot_time = event.booking.start_hour;
                    let slot_duration = event.booking.time_length;
                    let start_time = moment(event.booking.date).add(parseInt(slot_time), 'm').format('HH:mm A');
                    let end_time = moment(event.booking.date).add(parseInt(slot_time) + parseInt(slot_duration), 'm').format('HH:mm A');
                    let content = '';
                    let interpreter = (event.booking.is_interpreter == 1 ? '<i class="fa fa-globe"></i>' + event.booking.int_language + '<br />': '');
                    let interpreter_booked = '';
                    let booking_validation = event.booking.data
                                            && event.booking.data.InterpreterBooked
                                            && event.booking.is_interpreter == 1;
                    if (    booking_validation
                            &&  event.booking.data.InterpreterBooked == 1){
                        interpreter_booked =  '<i class="fa fa-check green-orbit"></i> Interpreter Booked';
                    }
                    else if (booking_validation
                            && event.booking.data.InterpreterBooked == 0){
                    interpreter_booked = '<i class="fa fa-exclamation-triangle darkorange"></i> Interpreter Not Booked';
                    }

                    content = `<strong> ${event.booking.service.name } </strong><br />
                                Start: ${start_time} <br />
                                End: ${end_time}<br />
                                ${interpreter}
                                ${interpreter_booked}`;
                    $(element).popover({
                        html: true,
                        content: content,
                        trigger: 'hover',
                        placement: 'auto top',
                        container: 'body'
                    });
                }
                var title = element.find( '.fc-title' );
                title.html( title.text() );
            },
            eventSelected(event, jsEvent){
                if(event.hasOwnProperty('booking')) {
                    for (let index = 0; index < this.services.length; index++) {
                        if(this.services[index].BookingServiceId == event.booking.service_id){
                            event.booking.orbit_service = this.services[index];
                        }
                    }
                    event.booking.booking_time = moment(event.booking.date).add(parseInt(event.booking.start_hour), 'm').format('h:mm A');
                    event.booking.date = moment(event.booking.date).format('YYYY-MM-DD');
                    this.$emit('update:current_booking', event.booking);
                    $("#bookingInfo").modal("show");
                }
                $(".popover").popover('hide');
            },
            initCalendar(response) {
                const self = this;
                return new Promise(function(resolve, reject) {
                    self.events = [];
                    self.addBookedEventsToCalendar(response);
                    resolve();
                });
            },
            addBookedEventsToCalendar(appts){
                let self = this;
                for (let index = 0; index < appts.length; index++) {
                    let appointment = appts[index];
                    let client = appts[index].client;
                    let slot_text = 'Name not indicated';

                    if(client){
                        let int_icon = (appointment.int_language ? '<i class="fa fa-globe"></i> ' : '');
                        slot_text = (client.hasOwnProperty('first_name') && client.hasOwnProperty('last_name') ?  client.first_name + ' ' + client.last_name : 'Name not indicated');
                        slot_text = (( slot_text.length > 16 ) ? slot_text.slice(0,14) + '...' : slot_text);
                        slot_text = int_icon + slot_text;
                    }

                    if(appointment.data){
                        try {
                            appointment.data = JSON.parse(appointment.data);
                        } catch (e) {
                            //all set the info was parsed as Object
                        }
                    }
                    self.service_colors[appts[index].service_id] = appointment.service.color;
                    let cal_event = {
                            title: slot_text,
                            start: moment(appointment.date).add(parseInt(appointment.start_hour), 'm'),
                            end: moment(appointment.date).add(parseInt(appointment.start_hour) + parseInt(appointment.time_length), 'm'),
                            booking: appts[index],
                            color: appointment.service.color,
                            textColor: getOptimalContrastText(appointment.service.color),
                            sv_id: appts[index].service_id,
                            free_slot: false,
                            editable: false
                    };
                    self.events.push(cal_event);
                }
            },
            deleteBookingEvent : function() {
                const self = this;
                EventBus.$on('delete_booking', (booking_id) => {
                    for(let i = 0 ; i < self.events.length ; i++) {
                        if(self.events[i].booking.id == booking_id) {
                            console.log(i, self.events[i]);
                            self.events.splice(i, 1);
                            this.$emit('update:reset_booking_to_delete',0);
                        }
                    }
                    for(let i = 0 ; i < self.initial_events.length ; i++) {
                        if(self.initial_events[i].booking.id == booking_id) {
                            self.initial_events.splice(i, 1);
                        }
                    }
                });
            },
            updateContactBookingEvent : function() {
                const self = this;
                EventBus.$on('update_booking', (field,booking) => {
                    if(field == 'client.first_name' || field == 'client.last_name' || field == 'client.contact') {
                        for(let i = 0 ; i < self.events.length ; i++) {
                            if(self.events[i].booking.client && self.events[i].booking.client.id == booking.client.id) {
                                switch(field) {
                                    case 'client.first_name':
                                        self.events[i].booking.client.first_name = booking.client.first_name;
                                        break;
                                    case 'client.last_name':
                                        self.events[i].booking.client.last_name = booking.client.last_name;
                                        break;
                                    case 'client.contact':
                                        self.events[i].booking.client.contact = booking.client.contact;
                                        break;
                                    }
                                let int_icon = (self.events[i].booking.int_language ? '<i class="fa fa-globe"></i> ' : '');
                                self.events[i].title = int_icon + (self.events[i].booking.client.hasOwnProperty('first_name') && self.events[i].booking.client.hasOwnProperty('last_name') ?  self.events[i].booking.client.first_name + ' ' + self.events[i].booking.client.last_name : 'Name not indicated');
                            }
                        }

                    }
                });
            }
        },
        watch:{
            service_provider_id: function(){
                this.getEventsBySelectedSP();
            },
            booking_to_delete : function() {
                let self = this;
                if(self.booking_to_delete != 0) {
                    for(let i = 0 ; i < self.events.length ; i++) {
                        if(self.events[i].booking.id == self.booking_to_delete) {
                            self.events.splice(i, 1);
                            this.$emit('update:reset_booking_to_delete',0);
                        }
                    }
                }
            },
            booking_to_update : function() {
                let self = this;
                let int_icon = (self.current_booking.int_language ? '<i class="fa fa-globe"></i> ' : '');
                let slot_time = self.current_booking.start_hour;
                let slot_duration = self.current_booking.time_length;
                if(self.booking_to_update != 0) {
                    for(let i = 0 ; i < self.events.length ; i++) {
                        if(self.events[i].booking.id == self.booking_to_update) {
                            self.events[i].title = int_icon + (self.current_booking.client.hasOwnProperty('first_name') && self.current_booking.client.hasOwnProperty('last_name') ?  self.current_booking.client.first_name + ' ' + self.current_booking.client.last_name : 'Name not indicated');
                            self.events[i].start = moment(self.current_booking.date).add(parseInt(slot_time), 'm');
                            self.events[i].end   = moment(self.current_booking.date).add(parseInt(slot_time) + parseInt(slot_duration), 'm');
                            this.$emit('update:booking_to_update',0);
                        }
                    }
                }
            },
            selectedServices: function () {
                this.filterEvents();
            }
        },
        mounted() {
            this.deleteBookingEvent();
            this.updateContactBookingEvent();
        },
    }

</script>
