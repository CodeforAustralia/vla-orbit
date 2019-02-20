import Vue from 'vue';
const EventBus = new Vue();
export default EventBus;
//Example Followed
//https://medium.com/@andrejsabrickis/https-medium-com-andrejsabrickis-create-simple-eventbus-to-communicate-between-vue-js-components-cdc11cd59860

//Example
/*
// component-a.js
import Vue from 'vue';
import EventBus from './event-bus';
Vue.component('component-a', {
    ...
    methods: {
        emitMethod() {
            EventBus.$emit('EVENT_NAME', payLoad);
        }
    }
});

*/