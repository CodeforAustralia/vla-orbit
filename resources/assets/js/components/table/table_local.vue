<template>
    <div>
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
                    <th>Last Notification</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="service in tableData" :key="service.ServiceId">
                    <th scope="row"> <input id="service.ServiceId" type="checkbox" class="service_check"> </th>
                    <td>{{ service.ServiceId }}</td>
                    <td>{{ service.ServiceName }}</td>
                    <td>{{ service.Email }}</td>
                    <td>{{ service.ServiceProviderName }}</td>
                    <td>{{ service.ServiceProviderTypeName }}</td>
                    <td>{{ service.UpdatedOn }}</td>
                    <td>{{ service.last_notification }}</td>
                    <td><button class="btn btn-xs green">Send</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import Vue from 'vue';
    import axios from 'axios';
    import moment from 'moment';
    export default {

        data () {
            return {
                url: '/service/list_without_update/',
                days: 90,
                tableData: [],
                select_all: false,
                sort_order: 'asc',
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
            fetchData() {
                let self = this;
                let dataFetchUrl = `${this.url}${this.days}`;
                axios.get(dataFetchUrl)
                    .then(({ data }) => {
                        self.tableData = data.data;
                    }).catch(error => {
                        self.tableData = [];
                        self.fetchData();
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
                let service_checks = [...document.querySelectorAll('.service_check')];
                if(this.select_all){
                    service_checks.map(item=> item.checked = true );
                } else {
                    service_checks.map(item=> item.checked = false );
                }
            }
        },

        created() {
            this.fetchData();
        }

    }
</script>