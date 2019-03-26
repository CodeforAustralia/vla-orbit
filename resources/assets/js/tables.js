require('./bootstrap')

import DataTable from './components/datatable/DataTable'

const dTable = new Vue({
    el: '#dTables',
    components: {
        DataTable
    }
})