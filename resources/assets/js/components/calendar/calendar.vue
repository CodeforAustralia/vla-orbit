<template>
	<full-calendar
    :config="config"
    :events="events"
	@event-selected="eventSelected"
	@event-render="eventRendered"
    />
</template>

<script>
	import moment from 'moment'
    import axios from 'axios';
    import EventBus from '../../utils/event-bus';

	export default {
        props: ['service_provider_id',
                'current_booking',
                'booking_to_delete',
                'booking_status_options',
                'booking_to_update'
                ],
        data () {
            let self = this;
            return {
                    services: {},
                    unavailable: {},
                    events: [
                    ],
                    config: {
                        header: {
                            left: 'title',
                            center: '',
                            right: 'prev,next today,month,agendaWeek,agendaDay,listMonth'
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
                                self.initCalendar(response.bookings);
                                self.services = response.services;
                                $("#contentLoading").modal('hide');
                            }
                        }]
                    },
            }
        },
        methods: {
            eventRendered(event, element, view){
                if(event.hasOwnProperty('booking')) {
                    let slot_time = event.booking.start_hour;
                    let slot_duration = event.booking.time_length;
                    let start_time = moment(event.booking.date).add(parseInt(slot_time), 'm').format('HH:mm A');
                    let end_time = moment(event.booking.date).add(parseInt(slot_time) + parseInt(slot_duration), 'm').format('HH:mm A');
                    let content = '';
                    let interpreter = (event.booking.is_interpreter == 1 ? '<i class="fa fa-globe"></i> ' + event.booking.int_language : '');
                    content = `<strong> ${event.booking.service.name } </strong><br />
                                Start: ${start_time} <br />
                                End: ${end_time}<br />
                                ${interpreter}`;
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
                this.events = [];
                setTimeout(() => {
                    this.addEventsToCalendar(response);
                }, 100);
            },
            addEventsToCalendar(appts){
                let self = this;
                for (let index = 0; index < appts.length; index++) {
                    let appointment = appts[index];
                    let client = appts[index].client;
                    let slot_text  = '';

                    if(client){
                        let int_icon = (appointment.int_language ? '<i class="fa fa-globe"></i> ' : '');
                        slot_text = (client.hasOwnProperty('first_name') && client.hasOwnProperty('last_name') ?  client.first_name + ' ' + client.last_name : 'Name not indicated');
                        if( slot_text.length > 16 ) {
                            slot_text = slot_text.slice(0,14) + '...';
                        }
                        slot_text = int_icon + slot_text;
                    } else {
                        slot_text = 'Name not indicated';
                    }

                    if(appointment.data){
                        try {
                            appointment.data = JSON.parse(appointment.data);
                        } catch (e) {
                            //all set the info was parsed as Object
                        }
                    }

                    let slot_time = appointment.start_hour;
                    let slot_duration = appointment.time_length;
                    let start_time = moment(appointment.date).add(parseInt(slot_time), 'm');
                    let end_time = moment(appointment.date).add(parseInt(slot_time) + parseInt(slot_duration), 'm');
                    if(slot_text !== '') {
                        self.events.push({
                            title: slot_text,
                            start: start_time,
                            end: end_time,
                            editable: false,
                            booking: appts[index],
                            color: appointment.service.color,
                            textColor:self.getOptimalContrastText(appointment.service.color),

                        });

                    }
                }
            },
            getOptimalContrastText : function (color) {
                let self = this;
                //convert hex to rgb
                let rgb = self.convertHexToRGB(color);
                // get luminance
                let luminance = self.getLuminance(rgb);
                //get contrast ratio
                let white_contrast_ratio = 1.05 / (luminance + 0.05);
                let black_contrast_ratio = (luminance + 0.05) / 0.05;
                if(white_contrast_ratio > black_contrast_ratio) {
                    return 'white';
                }
                return 'black'

            },
            getLuminance: function (rgb) {

                let rg = Math.pow(rgb.r/269 +  0.0513, 2.4);
                let gg = Math.pow(rgb.g/269 +  0.0513, 2.4);
                let bg = Math.pow(rgb.b/269 +  0.0513, 2.4);
                if(rgb.r <= 10) {
                    rg = rgb.r/3294;
                }
                if(rgb.g <= 10) {
                    gg = rgb.g/3294;
                }
                if(rgb.b <= 10) {
                    bg = rgb.b/3294;
                }
                let luminance =  0.2126 * rg + 0.7152 * gg + 0.0722 * bg;
                return luminance;
            },
            convertHexToRGB : function (hex) {
                let short_hand_regex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
                hex = hex.replace(short_hand_regex, function(m, r, g, b) {
                    return r + r + g + g + b + b;
                });

                let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
                return result ? {
                    r: parseInt(result[1], 16),
                    g: parseInt(result[2], 16),
                    b: parseInt(result[3], 16)
                } : null;
            },
            deleteBookingEvent : function() {
                var self =this;
                EventBus.$on('delete_booking', (booking_id) => {
                    for(let i = 0 ; i < self.events.length ; i++) {
                        if(self.events[i].booking.id == booking_id) {
                            self.events.splice(i, 1);
                            this.$emit('update:reset_booking_to_delete',0);
                        }
                    }
                });
            },
            updateContactBookingEvent : function() {
                var self = this;
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
                let self = this;
                let today = moment().format('YYYY-MM-01');
                let in_a_month = moment().add(1, 'month').format('YYYY-MM-10');
                let url = '/booking/service_provider/booking/?start=' + today + '&end=' + in_a_month + '&sp_id=' + self.service_provider_id;

                $("#contentLoading").modal("show");
                if(self.service_provider_id > 0){
                    axios.get(url)
                        .then(function (response) {
                            self.initCalendar(response.data.bookings);
                            self.services = response.data.services;
                            self.$emit('update:booking_status_options', response.data.booking_status);
                            $("#contentLoading").modal("hide");
                            $(".modal-backdrop").remove();
                        })
                        .catch(function (error) {
                            $("#contentLoading").modal("hide");
                            $(".modal-backdrop").remove();
                        });
                }
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
        },
        mounted() {
            this.deleteBookingEvent();
            this.updateContactBookingEvent();
        },
    }

</script>
