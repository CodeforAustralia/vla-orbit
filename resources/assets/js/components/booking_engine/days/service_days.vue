<template>

    <div class="service_days">

        <div class="btn-group margin-bottom-20" data-toggle="buttons">
            <label class="btn btn-xs green active">
                <input type="radio" class="toggle"> <a v-on:click="makeActive('currentActive')" class="whiteLink" href="#"> Current Year</a> </label>
            <label class="btn btn-xs green">
                <input type="radio" class="toggle"> <a v-on:click="makeActive('nextActive')" class="whiteLink" href="#"> Next Year</a> </label>
        </div>

        <div id="current" v-show="isActiveTab('currentActive')">

            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#current_regular">Regular</a></li>
                <li><a data-toggle="tab" href="#current_interpreter">Interpreter</a></li>
            </ul>

            <div class="tab-content">
                <div id="current_regular" class="tab-pane fade in active">
                    <calendar-container v-bind:currentCalendar="calendars.current_year" tableClass="current"> </calendar-container>
                </div>

                <div id="current_interpreter" class="tab-pane fade">
                    <calendar-container v-bind:currentCalendar="calendars.current_year" tableClass="current_interpreter"> </calendar-container>
                </div>
            </div>

        </div>

        <div id="next" v-show="isActiveTab('nextActive')">

            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#next_regular">Regular</a></li>
                <li><a data-toggle="tab" href="#next_interpreter">Interpreter</a></li>
            </ul>

            <div class="tab-content">
                <div id="next_regular" class="tab-pane fade in active">
                    <calendar-container v-bind:currentCalendar="calendars.next_year" tableClass="next"> </calendar-container>
                </div>

                <div id="next_interpreter" class="tab-pane fade">
                    <calendar-container v-bind:currentCalendar="calendars.next_year" tableClass="next_interpreter"> </calendar-container>
                </div>
            </div>

        </div>

        <div class="col-sm-12 margin-top-10">
            <button class="btn green pull-right" id="" v-on:click="submitInfo">Submit</button>
        </div>

    </div>

</template>

<script>
    import SelectableDS from '../selectableDS';
    Vue.component('calendar-container', require('./calendar_container.vue'));
    export default {
        props:['service'],
        data() {
            return {
                calendars: {},
                choice: 'currentActive',
                ds_current: {},
                ds_current_interpreter: {},
                ds_next: {},
                ds_next_interpreter: {},
                sv_id: this.$root.sv_id,
            }
        },
        methods: {
            //Make tab/container visible or hidden
            makeActive: function(val) {
                this.choice = val;
            },
            //Check if a tab/container should be visible
            isActiveTab: function(val) {
                return this.choice === val;
            },
            //Get Calendar by service ID
            getCalendar(sv_id) {
                var self = this;
                let url = '/booking/services/days/' + sv_id;

                axios['get'](url, {})
                    .then(response => {
                        self.calendars = response.data;
                    })
                    .then(() => {
                        self.initDragSelect();
                        $("#contentLoading").modal("hide");
                    })
                    .catch(error => {
                        $("#contentLoading").modal("hide");
                        console.log(error);
                    });
            },
            //Initialize Drage and select object for regular and interpreter elements
            initDragSelect() {
                var self = this;
                //Initialize Drag Select in for calendars
                self.ds_current = new SelectableDS('.current_option_day');
                self.ds_current_interpreter = new SelectableDS('.current_interpreter_option_day');
                self.ds_next = new SelectableDS('.next_option_day');
                self.ds_next_interpreter = new SelectableDS('.next_interpreter_option_day');

                //Set previous selections
                self.ds_current.setInitialSelections('.current ', self.calendars.selected_current); // Pre select values for an specific service
                self.ds_current_interpreter.setInitialSelections('.current_interpreter ', self.calendars.selected_current_interpreter); // Pre select values for an specific service
                self.ds_next.setInitialSelections('.next ', self.calendars.selected_next); // Pre select values for an specific service
                self.ds_next_interpreter.setInitialSelections('.next_interpreter ', self.calendars.selected_next_interpreter); // Pre select values for an specific service
            },
            //TODO - Submit information to webservice
            submitInfo() {
                console.log('Selection:' + this.ds_current.getSelection().map( item => item.id ));
                console.log('Selection:' + this.ds_current_interpreter.getSelection().map( item => item.id ));
                console.log('Selection:' + this.ds_next.getSelection().map( item => item.id ));
                console.log('Selection:' + this.ds_next_interpreter.getSelection().map( item => item.id ));
            }
        },
        watch: {
            //Watch change of service
            service: function() {
                $("#contentLoading").modal("show");
                let self = this;
                if (typeof this.ds_current.clear === "function") {
                    self.ds_current.clear();
                    self.ds_current = {};
                    self.ds_current_interpreter.clear();
                    self.ds_current_interpreter = {};
                    self.ds_next.clear();
                    self.ds_next = {};
                    self.ds_next_interpreter.clear();
                    self.ds_next_interpreter = {};
                }
                this.getCalendar(this.service);
            }
        }
    }
</script>