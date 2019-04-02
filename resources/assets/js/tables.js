require('./bootstrap')

import DataTable from './components/datatable/DataTable'
import vueDebounce from 'vue-debounce'

Vue.use(vueDebounce)

const dTable = new Vue({
    el: '#dTables',
    components: {
        DataTable
    }
})