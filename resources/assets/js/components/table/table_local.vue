<template>
    <div>
        <div class="col-xs-9">
            <label class="col-xs-3 padding-top-5">Last update older than:</label>
            <select class="col-xs-2 input-sm" v-model="selected_day_range">
                <option value="30">30+ Days</option>
                <option value="60">60+ Days</option>
                <option value="90">90+ Days</option>
            </select>
            <button type="button" class="btn btn-sm btn-default main-green margin-left-15" @click="notify_all">Notify all</button>
        </div>
        <div class="col-xs-3">
            <span class="pull-right padding-top-5">Total Services ({{total_services}})</span>
        </div>
        <div class="col-xs-12">
            <table class="table">
                <thead>
                    <tr>
                        <th><input id="service.ServiceId" type="checkbox" v-model="select_all"></th>
                        <th @click="orderNum('ServiceId')">Id</th>
                        <th @click="orderAlph('ServiceName')">Service Name</th>
                        <th>Email</th>
                        <th @click="orderAlph('ServiceProviderName')">Service Provider</th>
                        <th @click="orderAlph('ServiceProviderTypeName')">Type</th>
                        <th @click="orderDate('UpdatedOn')">Updated</th>
                        <th @click="orderDate('last_notification')">Last Notification</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="service in tableData" :key="service.ServiceId">
                        <th scope="row"> <input :id="service.ServiceId" type="checkbox" class="service_check" v-model="checked_services" :value="service.ServiceId"> </th>
                        <td>{{ service.ServiceId }}</td>
                        <td>{{ service.ServiceName }}</td>
                        <td>{{ service.Email }}</td>
                        <td>{{ service.ServiceProviderName }}</td>
                        <td>{{ service.ServiceProviderTypeName }}</td>
                        <td>{{ service.UpdatedOn }}</td>
                        <td>{{ service.last_notification }}</td>
                        <td><button class="btn btn-xs main-green" @click="notify_selected(service)">Send</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Start -->
        <div class="modal fade" id="notify_services" tabindex="-1" role="dialog" aria-labelledby="notify_services">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title bold col-xs-10">Notification Service</h4>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body padding-top-10">
                        <div class="container-fluid">
                        <!-- Top -->
                            <p v-if="checked_services.length <= 1">Send notification to service</p>
                            <p v-if="checked_services.length > 1">Send notification to {{ checked_services.length }} services</p>

                            <multiselect
                                v-model="template_selected"
                                :options="templates"
                                :multiple="false"
                                group-values="children"
                                group-label="text"
                                :group-select="false"
                                placeholder="Select a Template"
                                track-by="id"
                                label="text"
                                :searchable="true"
                                :close-on-select="true"
                                :show-no-results="false"
                                :show-labels="false"
                                ></multiselect>

                                <button type="button" class="btn main-green margin-top-15" @click="send_notifications">Send Notifications</button>
                        </div> <!-- Modal Body Close-->
                    </div><!-- Modal Content Close-->
                </div><!-- Modal Dialogue Close-->
            </div><!-- Modal Fade Close-->
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import axios from 'axios';
    import moment from 'moment';
    import Multiselect from 'vue-multiselect';
    import VueSweetalert2 from 'vue-sweetalert2';

    Vue.use(VueSweetalert2);

    export default {
        components: {
            Multiselect
        },

        data () {
            return {
                url: '/service/list_without_update/',
                tableData: [],
                checked_services: [],
                select_all: false,
                sort_order: 'asc',
                selected_day_range: 90,
                submit_url: '/service/send_notification',
                total_services: 0,
                templates: [],
                template_selected: [],
                headers: [
                    'ServiceId',
                    'ServiceName',
                    'Email',
                    'ServiceProviderName',
                    'ServiceProviderTypeName',
                    'UpdatedOn',
                    'last_notification'
                ]
            }
        },

        methods: {
            send_notifications() {
                let self = this;
                if(this.checked_services.length < 1 || !this.template_selected.hasOwnProperty('id')) {
                    self.$swal('Please choose a template.', '', 'error');
                } else {
                    axios.post(this.submit_url, { template_id: self.template_selected.id, services: self.checked_services })
                        .then(function (response) {
                            console.log(response);
                            let email_to_admin = response.data.data.email_to_admin;
                            let email_to_lho = response.data.data.email_to_lho;
                            let message_to_admin = `${email_to_admin} Emails sent to service admins`;
                            let message_to_lho = `${email_to_lho} Emails sent to LHO Email`;
                            let message = '';
                            if( email_to_admin == 0 ){
                                message = message_to_lho;
                            }
                            if( email_to_lho == 0 ){
                                message = message_to_admin;
                            }
                            if(email_to_admin == 0 && email_to_lho == 0 ) {
                                message = `${message_to_admin} and ${message_to_lho}`;
                            }
                            $("#notify_services").modal("hide");
                            self.$swal(message, '', 'success');
                            self.fetchData();
                        })
                        .catch(function (error) {
                            self.$swal('Please try again.', '', 'error');
                        });
                }
            },
            get_email_templates(){
                let self = this;
                let dataFetchUrl = '/no_reply_emails/listTemplatesBySectionFormated';
                axios.get(dataFetchUrl)
                    .then(({ data }) => {
                        self.templates = data;
                    }).catch(error => {
                        self.get_email_templates();
                    });
            },
            notify_selected(service){
                document.getElementById(service.ServiceId).checked = true;
                if(!this.checked_services.includes(service.ServiceId)) {
                    this.checked_services.push(service.ServiceId);
                }
                $('#notify_services').modal('show');
            },
            notify_all(){
                this.select_all = true;
                $('#notify_services').modal('show');
            },
            fetchData() {
                $('#contentLoading').modal('show');
                let self = this;
                let dataFetchUrl = `${this.url}${this.selected_day_range}`;
                axios.get(dataFetchUrl)
                    .then(({ data }) => {
                        self.tableData = data.data;
                        self.total_services = data.data.length;
                        $('#contentLoading').modal('hide');
                    }).catch(error => {
                        self.tableData = [];
                        self.fetchData();
                        $('#contentLoading').modal('hide');
                    });
            },
            change_order() {
                if (this.sort_order == 'asc') {
                    this.sort_order = 'desc';
                } else {
                    this.sort_order = 'asc';
                }
            },
            orderAlph(column) {
                this.tableData.sort((a, b) => {
                    if (this.sort_order == 'asc') {
                        return a[column].localeCompare(b[column]);
                    } else {
                        return b[column].localeCompare(a[column])
                    }
                });
                this.change_order();
            },
            orderNum(column) {
                const self = this;
                this.tableData.sort((a, b) => {
                    if(self.sort_order == 'asc'){
                        return a[column] - b[column];
                    } else {
                        return b[column] - a[column];
                    }
                });
                this.change_order();
            },
            orderDate(column) {
                const self = this;
                this.tableData.sort((a, b) => {
                    let date1 = a[column].split('-');
                    let date2 = b[column].split('-');
                    a = new Date(`${date1[2]}-${date1[1]}-${date1[0]}`);
                    b = new Date(`${date2[2]}-${date2[1]}-${date2[0]}`);
                    if(self.sort_order == 'asc'){
                        return a>b ? -1 : a<b ? 1 : 0;
                    } else {
                        return a>b ? 1 : a<b ? -1 : 0;
                    }
                });
                this.change_order();
            }
        },
        watch: {
            select_all() {
                const self = this;
                let service_checks = [...document.querySelectorAll('.service_check')];
                self.checked_services = [];
                if(self.select_all){
                    service_checks.map(item=> {
                        item.checked = true;
                        self.checked_services.push(parseInt(item.value));
                    } );
                } else {
                    service_checks.map(item=> item.checked = false );
                }
            },
            selected_day_range() {
                this.fetchData();
            },
        },

        created() {
            this.fetchData();
            this.get_email_templates();
        }

    }
</script>