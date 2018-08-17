<template>
    <div>
        <div class="form-group col-xs-12 margin-bottom-20">
            <h5>Choose Day</h5>
            <div class="col-sm-6 col-md-4">
                <input type="text" class="form-control input-medium" id="adhoc_date" name="adhoc_date" placeholder="dd-mm-yyyy">
            </div>
        </div>

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#regular_journey">Regular</a></li>
            <li><a data-toggle="tab" href="#interpreter_journey">Interpreter</a></li>
        </ul>

        <div class="tab-content">
            <div id="regular_journey" class="tab-pane fade in active">
                <journey-container v-bind:currentJourney="journey.regular" tableClass="current" v-on:reload-ds="initDragSelect"> </journey-container>
            </div>

            <div id="interpreter_journey" class="tab-pane fade">
                <journey-container v-bind:currentJourney="journey.interpreter" tableClass="current_interpreter" v-on:reload-ds="initDragSelect"> </journey-container>
            </div>
        </div>

        <div class="form-group margin-top-10">
            <div class="col-sm-12 margin-top-10">
                <button class="btn green pull-left" v-on:click="submitInfo">Submit</button>
            </div>
        </div>
    </div>
</template>

<script>
    import SelectableDS from '../selectableDS';
    Vue.component('journey-container', require('./journey_container.vue'));
    export default {
        props:['service'],
        data() {
            return {
                choice: 'currentActive',
                date: '',
                ds_interpreter: {},
                ds_regular: {},
                journey: {},
                sv_id: this.$root.sv_id,
            }
        },
        methods: {
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_regular = new SelectableDS('#regular_journey .ds-button');
                self.ds_interpreter = new SelectableDS('#interpreter_journey .ds-button');
                //There are no previous selections
            },
            //TODO - Submit information to webservice
            submitInfo() {
                console.log('Selection:' + this.ds_regular.getSelection().map( item => item.id ));
                console.log('Selection:' + this.ds_interpreter.getSelection().map( item => item.id ));
            },
            initAdhocDate() {
                var self = this;
                let adhoc_date = document.getElementById('adhoc_date');
                let current_date = moment().format('DD-MM-YYYY');
                //Should be replaced for another vuejs library
                $(adhoc_date).datepicker({
                        format: "dd-mm-yyyy",
                        startDate: current_date,
                        daysOfWeekDisabled: [0, 6],
                        todayHighlight: true,
                        autoclose: true
                    })
                    .on('changeDate', function (event) {
                        self.date = moment(event.date).format('DD-MM-YYYY');
                    });
            }
        },
        watch: {
            //Watch change of service
            service: function() {
                $("#contentLoading").modal("show");
                if (typeof this.ds_regular.clear === "function" && typeof this.ds_interpreter.clear === "function") {
                    this.ds_regular.clear();
                    this.ds_interpreter.clear();
                }
                this.initDragSelect(this.service);
            }
        },
        mounted() {
            this.initAdhocDate();
        },
    }
</script>