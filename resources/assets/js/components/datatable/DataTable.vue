<template>
    <div class="data-table">
        <div class="form-group row">
            <div class="col-sm-6 col-md-7">
                <div v-if="showPrint">
                    <button class="btn blue"  @click="printTable">Print</button>
                    <download-excel
                        class = "btn green"
                        :data = "tableDataFiltered"
                        name    = "orbit.csv"
                        type    = "csv">
                        CSV
                    </download-excel>
                </div>
            </div>
            <label class="col-form-label font-weight-bold padding-top-10 col-sm-2 text-right" for="search" :placeholder="title.toLowerCase() + ' name'">Search</label>
            <div class="col-sm-4 col-md-3">
                <input type="text" v-debounce:500ms="searchFilter" id="search"  class="form-control form-control-sm">
            </div>
        </div>
        <table class="table" id="data_table">
            <thead>
                <tr>
                    <th v-for="column in columns" :key="column" class="table-head" @click="sortByColumn(column)">
                        {{ column | columnHead }}
                        <span v-if="column === sortedColumn">
                            <i v-if="order === 'asc' " class="fa fa-arrow-up"></i>
                            <i v-else class="fa fa-arrow-down"></i>
                        </span>
                    </th>
                    <th v-if="showUrl != '' || editUrl != '' " class="table-head"></th>
                </tr>
            </thead>
            <tbody>
                <tr class v-if="tableData.length === 0">
                    <td class="lead text-center" :colspan="columns.length + 1">No data found.</td>
                </tr>
                <tr v-for="data in tableDataFiltered" :key="data[identifier]" class="m-datatable__row" v-else>
                    <td v-for="(value, key) in data" v-bind:key="key">
                        <span v-if="key !== 'actions'">
                            {{ value }}
                        </span>
                        <span v-if="(showUrl != '' || editUrl != '' || deleteUrl != '') && key == 'actions' ">
                            <form method="GET" :action="deleteUrl + '/'  + data[identifier]" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                <input type="hidden" name="_token" :value="csrf">

                                <a :href="showUrl + '/' + data[identifier]" :id="'view-' + data[identifier]" class="btn btn-xs blue view-btn" :title="'Show ' + title " v-if="showUrl != '' && value.can_view">
                                    Show
                                </a>
                                <a :href="editUrlComposition(data[identifier])" class="btn btn-warning btn-xs" :title="'Edit ' + title" v-if="editUrl != '' && value.can_edit">
                                    Edit
                                </a>
                                <button type="submit" :dusk="'delete-' + title.toLowerCase() + '-' + data[identifier]" class="btn btn-danger btn-xs" :title="'Delete ' + title" :onclick="'return confirm(&quot;Delete ' + title + '?&quot;)'" v-if="deleteUrl != '' && value.can_delete">
                                    Delete
                                </button>

                            </form>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        <nav v-if="pagination && tableData.length > 0 && tableTotal > perPage">
            <ul class="pagination">
                <li class="page-item" :class="{'disabled' : currentPage === 1}">
                    <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">Previous</a>
                </li>
                <li v-for="(page, key2) in pagesNumber" class="page-item"
                    :class="{'active': page == pagination.current_page}"
                    :key="key2"
                >
                <a href="javascript:void(0)" @click.prevent="changePage(page)" class="page-link">{{ page }}</a>
                </li>
                <li class="page-item" :class="{'disabled': currentPage === pagination.last_page }">
                <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">Next</a>
                </li>
                <span style="margin-top: 8px;"> &nbsp; <i>Displaying {{ pagination.data.length }} of {{ pagination.total }} entries.</i></span>
            </ul>
        </nav>
    </div>
</template>

<script type="text/ecmascript-6">
    import Vue from 'vue'
    import { Printd } from 'printd'
    import JsonExcel from 'vue-json-excel'
    import buttonPermissions from './permissions'

    Vue.component('downloadExcel', JsonExcel)

    export default {
        props: {
            fetchUrl: { type: String, required: true },
            columns: { type: Array, required: true },
            showUrl: { type: String, required: true },
            editUrl: { type: String, required: true },
            deleteUrl: { type: String, required: true },
            title: { type: String, required: false },
            perPage: { type: String, required: true },
            showPrint: { type: Boolean, required:false, default:false },
            model: { type: String, required: false },
            identifier: { type: String, required: true },
        },
        data() {
            return {
                tableData: [],
                tableTotal: 0,
                url: '',
                pagination: {
                    meta: { to: 1, from: 1 }
                },
                offset: 4,
                currentPage: 1,
                sortedColumn: this.columns[0],
                search: '',
                order: 'desc',
                csrf: document.getElementsByName("_token")[0].content
            }
        },
        watch: {
            fetchUrl: {
                handler: function(fetchUrl) {
                    this.url = fetchUrl;
                },
                immediate: true
            }
        },
        created() {
            return this.fetchData();
        },
        computed: {
            /**
             * Remove extra attribute created on Laravel's end with row number
             */
            tableDataFiltered() {
                let self = this;
                return self.tableData.map(function(td) {
                        let filtered = {};
                        for (let index = 0; index < self.columns.length; index++) {
                            if(self.columns[index] in td) {
                                filtered[self.columns[index]] = td[self.columns[index]];
                            }
                        }
                        filtered.actions = buttonPermissions(self.model, td);
                        return filtered;
                    })
            },
            /**
             * Get the pages number array for displaying in the pagination.
             * */
            pagesNumber() {
                if (!this.pagination.to) {
                    return [];
                }
                let from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                let to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                let pagesArray = [];
                for (let page = from; page <= to; page++) {
                    pagesArray.push(page);
                }
                return pagesArray;
            },
            /**
             * Get the total data displayed in the current page.
             * */
            totalData() {
                return (this.pagination.to - this.pagination.from) + 1;
            }
        },
        methods: {
            editUrlComposition(id){
                return this.editUrl + '/' + id;
            },
            fetchData() {
                let dataFetchUrl = `${this.url}?page=${this.currentPage}&column=${this.sortedColumn}&order=${this.order}&per_page=${this.perPage}&search=${this.search}`;
                axios.get(dataFetchUrl)
                    .then(({ data }) => {
                        this.pagination = data;
                        this.pagination.meta = data;
                        if('data' in data) { //If no data then reset array
                            this.tableData = data.data;
                            this.tableTotal = data.total;
                        } else {
                            this.tableData = [];
                        }
                    }).catch(error => this.tableData = []);
            },
            /**
             * Get the serial number.
             * @param key
             * */
            serialNumber(key) {
                return (this.currentPage - 1) * this.perPage + 1 + key;
            },
            /**
             * Change the page.
             * @param pageNumber
             */
            changePage(pageNumber) {
                this.currentPage = pageNumber;
                this.fetchData();
            },
            /**
             * Sort the data by column.
             * */
            sortByColumn(column) {
                if (column === this.sortedColumn) {
                    this.order = (this.order === 'asc') ? 'desc' : 'asc';
                } else {
                    this.sortedColumn = column;
                    this.order = 'asc';
                }
                this.fetchData();
            },
            /**
             * Search function with delay
             * */
            searchFilter(val) {
                this.search = val;
                this.currentPage = 1; //Reset current page to initial one when searching
                this.fetchData();
            },
            printTable() {
                const d = new Printd();
                d.print( document.getElementById('data_table'));
            }
        },
        filters: {
            columnHead(value) {
                return value.split('_').join(' ').toUpperCase();
            }
        },

    }
</script>
