import "@babel/polyfill";
import Vue from 'vue';
import DataTable from './components/datatable/DataTable'
import vueDebounce from 'vue-debounce'

Vue.use(vueDebounce)

const dTable = new Vue({
    el: '#dTables',
    components: {
        DataTable
    }
})