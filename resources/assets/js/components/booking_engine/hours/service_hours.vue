<template>
    <div>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#regular">Regular</a></li>
            <li><a data-toggle="tab" href="#interpreter">Interpreter</a></li>
        </ul>

        <div class="tab-content">
            <div id="regular" class="tab-pane fade in active">
                <hours-container v-bind:currentSchedule="schedule.regular" tableClass="current" v-on:reload-ds="initDragSelect"> </hours-container>
            </div>

            <div id="interpreter" class="tab-pane fade">
                <hours-container v-bind:currentSchedule="schedule.interpreter" tableClass="current_interpreter" v-on:reload-ds="initDragSelect"> </hours-container>
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
    Vue.component('hours-container', require('./hours_container.vue'));
    export default {
        props:['service'],
        data() {
            return {
                choice: 'currentActive',
                ds_interpreter: {},
                ds_regular: {},
                schedule: {},
                sv_id: this.$root.sv_id,
            }
        },
        methods: {
            //Get schedules by service ID
            getSchedule(sv_id) {
                var self = this;
                let url = '/booking/services/hours/' + sv_id;

                axios['get'](url, {})
                    .then(response => {
                        self.schedule = response.data;
                    })
                    .then(() => {
                        self.initDragSelect();
                        $("#contentLoading").modal("hide");
                    })
                    .catch(error => {
                        console.log(error);
                        $("#contentLoading").modal("hide");
                    });
            },
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_regular = new SelectableDS('#regular .ds-button');
                self.ds_interpreter = new SelectableDS('#interpreter .ds-button');

                //Set previous selections
                self.ds_regular.setInitialSelections('#regular .ds-button', self.schedule.regular.days); // Pre select values for an specific service
                self.ds_interpreter.setInitialSelections('#interpreter .ds-button', self.schedule.interpreter.days); // Pre select values for an specific service
            },
            //TODO - Submit information to webservice
            submitInfo() {
                console.log('Selection:' + this.ds_regular.getSelection().map( item => item.id ));
                console.log('Selection:' + this.ds_interpreter.getSelection().map( item => item.id ));
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
                this.getSchedule(this.service);
            }
        }
    }
</script>