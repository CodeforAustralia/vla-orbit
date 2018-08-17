<template>

    <div :class="tableClass">
        <div class="form-group">
            <span>Change time display: <small>(in minutes)</small></span>
            <button type="button" class="btn btn-xs default" v-on:click="setTimeStructure('quarter_hour')">15</button>
            <button type="button" class="btn btn-xs default" v-on:click="setTimeStructure('half_hour')">30</button>
            <button type="button" class="btn btn-xs default" v-on:click="setTimeStructure('hour')">60</button>
        </div>
        <div class="form-group margin-top-10 hours_selection">
            <div class="col-sm-12">
                <div class="row top_dates noSelect">
                    <div class="day_col">&nbsp;</div>
                    <div class="hours_col">00</div>
                    <div class="hours_col">01</div>
                    <div class="hours_col">02</div>
                    <div class="hours_col">03</div>
                    <div class="hours_col">04</div>
                    <div class="hours_col">05</div>
                    <div class="hours_col">06</div>
                    <div class="hours_col">07</div>
                    <div class="hours_col">08</div>
                    <div class="hours_col">09</div>
                    <div class="hours_col">10</div>
                    <div class="hours_col">11</div>
                    <div class="hours_col">12</div>
                    <div class="hours_col">13</div>
                    <div class="hours_col">14</div>
                    <div class="hours_col">15</div>
                    <div class="hours_col">16</div>
                    <div class="hours_col">17</div>
                    <div class="hours_col">18</div>
                    <div class="hours_col">19</div>
                    <div class="hours_col">20</div>
                    <div class="hours_col">21</div>
                    <div class="hours_col">22</div>
                    <div class="hours_col">23</div>
                </div>
                <div v-for="(day, id) in week_days" :key="id">
                    <div class="row week_days">
                        <div class="day_col noSelect" v-text="day"></div>
                        <div class="hours_col" v-for="(n,i) in 24" :key="i">
                            <div v-html="drawHours(day,n)"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>


<script>
    export default {
        props: {
            currentSchedule: Object,
            tableClass: String
        },
        data() {
            return {
                week_days: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
                time_structure_active: 'hour',
                time_structure: {
                    hour: {
                        lenght: 60,
                        columns: 24,
                        el_in_col: 1
                    },
                    half_hour: {
                        lenght: 30,
                        columns: 48,
                        el_in_col: 2
                    },
                    quarter_hour: {
                        lenght: 15,
                        columns: 96,
                        el_in_col: 4
                    }
                }
            }
        },
        methods: {
            setTimeStructure(time_structure_name) {
                let self = this;
                self.time_structure_active = time_structure_name; //i.e Hour, Half hour or quarter hour

                $("#contentLoading").modal("show");
                setTimeout(function(){
                    self.$emit('reload-ds',true); //This emits a message to the parent component in order to re-initialize drag all drag and select buttons
                    $("#contentLoading").modal("hide");
                }, 1000);

            },
            drawHours(week_day, hour) {
                let self = this;
                let current_time_structure = self.time_structure[self.time_structure_active];
                let standard_container = '';

                let initial_minute =  current_time_structure.el_in_col * (hour-1);

                for (let index = 0; index < current_time_structure.el_in_col; index++) {
                    let element_id = (initial_minute + index) * current_time_structure.lenght;
                    let btn = '<button type="button" class="btn ds-button ' + self.time_structure_active + '" id="' + week_day + '-' + element_id + '">&nbsp;</button>';
                    standard_container += btn;
                }
                return standard_container;
            }
        },
        watch: {
            currentSchedule: function () {
                this.time_structure_active = this.currentSchedule.time_name;
            }
        }
    }
</script>