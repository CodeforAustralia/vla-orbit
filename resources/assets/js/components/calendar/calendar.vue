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

	export default {
        props: ['service_provider_id', 'current_booking'],
        data () {
            let self = this;
            return {
                    regular: {},
                    interpreter: {},
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
                            month: {
                                eventLimit: 5 // adjust to 6 only for agendaWeek/agendaDay
                            },
                            agendaWeek:{
                                columnHeaderFormat:'ddd D/M'
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
                                setTimeout(() => { //Line necessary to avoid duplication on events with no names and info on them
                                    self.initCalendar(response);
                                    $("#contentLoading").modal('hide');
                                }, 1000);
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
                    $(element).popover({
                        html: true,
                        content: 'Start: ' + start_time + '<br />End: ' + end_time,
                        trigger: 'hover',
                        placement: 'auto top',
                        container: 'body'
                    });
                }
            },
            eventSelected(event, jsEvent){
                console.log(event, jsEvent);
                if(event.hasOwnProperty('booking')) {
                    //this.current_booking = event.booking;
                    this.$emit('update:current_booking', event.booking);
                    $("#bookingInfo").modal("show");
                }
                $(jsEvent.target).popover('toggle');
            },
            initCalendar(response) {
                this.events = [];
                this.addEventsToCalendar(response);
            },
            addEventsToCalendar(appts){
                let self = this;
                for (let index = 0; index < appts.length; index++) {
                    let appointment = appts[index];
                    let client = appts[index].client;
                    let slot_text  = '';
                    if(client){
                        slot_text = (client.hasOwnProperty('first_name') && client.hasOwnProperty('last_name') ?  client.first_name + ' ' + client.last_name : 'Name not indicated');
                    } else {
                        slot_text = 'Name not indicated';
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
                            booking: appts[index]
                        });
                    }
                }
            }
        },
        watch:{
            service_provider_id: function(){
                let self = this;
                let today = moment().format('YYYY-MM-01');
                let in_a_month = moment().add(1, 'month').format('YYYY-MM-10');
                let url = '/booking/service_provider/booking/?start=' + today + '&end=' + in_a_month + '&sp_id=' + self.service_provider_id;

                $("#contentLoading").modal("show");
                axios.get(url)
                    .then(function (response) {
                        self.initCalendar(response.data);
                        $("#contentLoading").modal("hide");
                    })
                    .catch(function (error) {
                        $("#contentLoading").modal("hide");
                    });
            }
        },
        mounted() {
        },
    }

</script>
