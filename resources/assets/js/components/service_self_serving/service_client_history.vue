<template>
    <div>

        <histor-widget title="General Settings" :logInfo="serviceHistory.general_settings"></histor-widget>
        <histor-widget title="Catchments" :logInfo="serviceHistory.catchment_info"></histor-widget>
        <!-- <histor-widget title="Eligibility Questions" :logInfo="serviceHistory.eligibility_questions"></histor-widget>
        <histor-widget title="Legal Matters" :logInfo="serviceHistory.legal_matters"></histor-widget>
        <histor-widget title="Actions Refer" :logInfo="serviceHistory.actions_refer"></histor-widget>
        <histor-widget title="Actions for Book" :logInfo="serviceHistory.actions_book"></histor-widget>
        <histor-widget title="Actions E_refer" :logInfo="serviceHistory.actions_e_refer"></histor-widget>
        <histor-widget title="E_referral Forms" :logInfo="serviceHistory.e_referral_forms"></histor-widget> -->

    </div>
</template>

<script>
import axios from 'axios';
import HistorWidget from './history_widget.vue';
export default {
    props:['current_service'],
    components: {
        historWidget: HistorWidget
    },
    data() {
        return {
            service: {},
            serviceHistory: {}
        }
    },
    methods: {
        getServiceHistory() {
            $("#contentLoading").modal("show");
            let self = this;
            let url = `/service_log/${self.current_service.ServiceId}`;
            axios.get(url)
                .then(response => {self.serviceHistory = response.data; console.log(response.data); $("#contentLoading").modal("hide");})
                .catch(error => this.getServiceHistory());
            //self.current_service.ServiceId
        }
    },
    mounted() {
        this.getServiceHistory();
    },
}
</script>