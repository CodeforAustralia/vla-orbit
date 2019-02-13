import Vue from 'vue';
import axios from 'axios';
import moment from 'moment';
//This is a small hack to the suggested way...
import DatatableFactory from 'vuejs-datatable/dist/vuejs-datatable.esm.js';
DatatableFactory.useDefaultType(false)
    .registerTableType('datatable', function (table_type) {
        table_type.mergeSettings({
            table: {
                class: 'table table-striped table-bordered table-hover dataTable no-footer',
                sorting: {
                    classes: {
                        canSort: ['sorting'],
                        sortNone: ['fa', 'fa-sort'],
                        sortAsc: ['fa', 'fa-sort-asc', 'center', 'sorting_asc'],
                        sortDesc: ['fa', 'fa-sort-desc', 'center', 'sorting_desc'],
                    }
                }
            },
            pager: {
                classes: {
                    pager: 'pagination text-center',
                    selected: 'current'
                }
            }
        });
    });

Vue.use(DatatableFactory);

Vue.component('actionBtns', {
    template: `<div>
                    <button v-if="displayButton()" class="btn btn-xs green remind-booking col-xs-12 col-md-6" @click="sendReminder">Send Reminder</button>
                    <button class="btn btn-xs btn-danger col-xs-12 col-md-6" @click="deleteBooking">Delete</button>
                </div>`,
    props: ['row', 'column'],
    methods: {
        displayButton: function () {
            return this.row.data && this.isFutureBooking() && this.row.data.IsSafeSMS == 1;
        },
        isFutureBooking: function () {
            const today = moment().format('YYYY-MM-DD');
            const appt_date = moment(this.row.date).format('YYYY-MM-DD');
            const is_after = moment(appt_date).isAfter(today);
            return is_after;
        },
        sendReminder: function () {
            //this.row
            var self = this;
            let url = "/booking/sendSmsReminder";
            const booking = {
                Mobile: self.row.client.contact,
                FirstName: self.row.client.first_name,
                BookingDate: self.row.date,
                BookingTime: self.row.time,
                ServiceId: self.row.service_id,
                IsSafeSMS: self.row.data.IsSafeSMS,
                RefNo: self.row.id,
                template: '',
                IsSafeSMS: 1,
            };
            $("#contentLoading").modal("show");
            axios.get(url, {
                    params: {
                        booking: booking
                    }
                })
                .then(function (response) {
                    if (self.row.sms_date) {
                        self.row.sms_date += ', ' + moment().format('YYYY-MM-DD');
                    } else {
                        self.row.sms_date = moment().format('YYYY-MM-DD');
                    }
                    alert('Message sent.');
                    $("#contentLoading").modal("hide");
                })
                .catch(function (error) {
                    $("#contentLoading").modal("hide");
                });
        },
        deleteBooking: function () {
            var self = this;
            let confirmation = confirm("Are you sure that you want to delete it?\n To confirm press OK or Cancel.");
            if (confirmation == true) {
                $("#contentLoading").modal("show");
                let url = '/booking/' + self.row.id;
                axios.delete(url)
                    .then(function (response) {
                        alert(response.data)
                        window.location.reload();
                        $("#contentLoading").modal("hide");
                    })
                    .catch(error => {
                        alert('Please refresh the page.');
                        $("#contentLoading").modal("hide");
                    });
            }
        }
    },
});

new Vue({
    el: '#datatable',
    data: {
        filter: '',
        columns: [
            { label: 'id', field: 'id' },
            { label: 'Date', field: 'date', headerClass: 'class-in-header second-class' },
            { label: 'Time', field: 'time' },
            { label: 'Service', field: 'ServiceName' },
            { label: 'First Name', field: 'client.first_name' },
            { label: 'last Name', field: 'client.last_name' },
            { label: 'Phone', field: 'client.contact' },
            { label: 'Sent SMS', field: 'sms_date' },
            {
                label: '',
                component: 'action-btns',
                sortable: false
            },

        ],
        rows: [],
        page: 1,
        per_page: 100
    },
    methods: {
        getBookings: function () {
            $("#contentLoading").modal("show");
            let self = this;
            let url = '/booking/user';
            axios['get'](url, {})
                .then(response => {
                    self.rows = response.data;
                    $("#contentLoading").modal("hide");
                })
                .catch(error => {
                    $("#contentLoading").modal("hide");
                    alert('Something went wrong, please refresh the page.');
                });
        }
    },
    mounted() {
        this.getBookings();
    },
});