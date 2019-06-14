<template>
    <div class="form">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-xs-5">
                    <p class="caption-subject font-purple-soft bold uppercase margin-bottom-10">Service Details</p>
                </div>
                <div class="col-xs-7 text-right">
                    <label for="Status"><small>Show this service in results?</small></label>
                    <toggle-button
                        v-model="status"
                        :labels="{checked: 'Yes', unchecked: 'No'}"
                        :sync="true"
                        :color="{checked:'#32c5d2', unchecked:'#e73d4a'}"/>
                </div>
                <div class="col-sm-12">
                    <label for="service_provider_id">Service Provider: <small>if not listed go to the Service Provider tab in left sidebar to create new</small></label>
                </div>
                <div class="col-sm-7">
                    <multiselect
                        v-model="service_provider_selected"
                        label="ServiceProviderName"
                        key="ServiceProviderId"
                        id="service-provider-select"
                        placeholder="Select Service..."
                        open-direction="bottom"
                        :options='service_providers'
                        :multiple="false"
                        :searchable="true"
                        :close-on-select="true"
                        :show-no-results="false"
                        :show-labels="false"
                        :allow-empty="false"
                        ></multiselect>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="name">Service Name: <small>eg. Fines Drop-in Clinic</small></label>
                </div>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="name" name="name" v-model="current_service.ServiceName" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="service_type_id">Service Type:</label>
                </div>
                <div class="col-sm-7">
                    <multiselect
                    v-model="service_type_selected"
                    label="ServiceTypeName"
                    key="ServiceTypelId"
                    id="service-type-select"
                    placeholder="Select Service Type..."
                    open-direction="bottom"
                    :options='service_types'
                    :multiple="false"
                    :searchable="true"
                    :close-on-select="true"
                    :show-no-results="false"
                    :show-labels="false"
                    :allow-empty="false"
                    >
                    </multiselect>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="service_level_id">Service Level:</label>
                </div>
                <div class="col-sm-7">
                    <multiselect
                    v-model="service_level_selected"
                    label="ServiceLevelName"
                    key="ServiceLevelId"
                    id="service-level-select"
                    placeholder="Select Service Level..."
                    open-direction="bottom"
                    :options='service_levels'
                    :multiple="false"
                    :searchable="true"
                    :close-on-select="true"
                    :show-no-results="false"
                    :show-labels="false"
                    :allow-empty="false"
                    >
                    </multiselect>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="wait">Wait Time: <small>eg. 2 weeks for an appointment or 20 min. wait for a phone service</small></label>
                </div>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="wait" name="wait" v-model="current_service.Wait"  required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="description">Description: <small>incl. info on how to proceed, what to expect and what to prepare ahead of making contact</small></label>
                    <vue-mce
                        id="description"
                        class="form-control"
                        v-model="current_service.Description"
                        :config="config_description"
                        name="description"/>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="notes">Notes: <small>incl. info about your service for other admins to know (Clients won't receive/see this information)</small></label>
                    <vue-mce
                        id="notes"
                        class="form-control"
                        v-model="current_service.Notes"
                        :config="config_notes"
                        name="notes"/>
                </div>

                <div class="col-sm-12" v-if="service_notes_log.length > 0">
                    <label for="notes">Old Notes:</label>
                        <ul class="feeds" v-html="show_log_notes">
                        </ul>
                </div>

            </div>

            <div class="form-group">
                <div class="col-xs-5">
                    <p class="caption-subject font-purple-soft bold uppercase margin-bottom-10 margin-top-10">Contact Details</p>
                </div>
                <div class="col-sm-12">
                    <label for="location">Location: <small>eg. 123 Fitzroy St, Brunswick 3056. If none put #</small></label>
                </div>
                <div class="col-sm-7">
                    <input type="location" class="form-control" id="location" name="location" v-model="current_service.Location" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="phone">Phone Number: <small>eg. 0444 333 222 The number is for clients to contact the service. If none put #</small></label>
                </div>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="phone" name="phone" v-model="current_service.Phone"  required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="email">Email: <small>any e-referrals will be sent to this address</small></label>
                </div>
                <div class="col-sm-7">
                    <input type="email" class="form-control" id="email" name="email" v-model="current_service.Email" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="URL">Website: <small>eg. www.codeforaustralia.org. Do not include http://</small></label>
                </div>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="URL" name="URL" v-model="current_service.URL" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="OpenningHrs">Opening Hours: <small>eg. Wednesdays 2-4pm or Weekdays 9am - 5pm</small></label>
                </div>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="OpenningHrs" name="OpenningHrs" v-model="current_service.OpenningHrs" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <p class="caption-subject font-purple-soft bold uppercase margin-bottom-10">Catchment Area</p>
                </div>
                <div class="col-sm-12">
                    <p><small>If the service has a catchment add the local government areas or suburbs here. If no catchment leave blank</small></p>

                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_lga" data-toggle="tab"> LGA </a>
                        </li>
                        <li>
                            <a href="#tab_suburb" data-toggle="tab"> Suburb </a>
                        </li>
                        <li>
                            <a href="#tab_postcode" data-toggle="tab"> Postcode </a>
                        </li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane fade active in" id="tab_lga">
                            <multiselect
                            v-model="lgas_selected"
                            label="text"
                            key="id"
                            id="lga"
                            track-by="id"
                            open-direction="top"
                            placeholder="Select LGA"
                            :options='lgas'
                            :multiple="true"
                            :searchable="true"
                            :close-on-select="true"
                            :show-no-results="false"
                            :show-labels="false"
                            name="lga[]"
                            >
                            </multiselect>

                        </div>

                        <div class="tab-pane fade" id="tab_suburb">
                            <multiselect
                            v-model="suburbs_selected"
                            label="text"
                            key="id"
                            id="suburbs"
                            track-by="id"
                            open-direction="top"
                            placeholder="Select Suburb"
                            :options='suburbs'
                            :multiple="true"
                            :searchable="true"
                            :close-on-select="true"
                            :show-no-results="false"
                            :show-labels="false"
                            name="suburbs[]"
                            >
                            </multiselect>
                        </div>

                        <div class="tab-pane fade" id="tab_postcode">
                            <input type="postcodes" class="form-control" id="postcodes" name="postcodes" v-model="catchments.Postcode">
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <button type="button" class="btn green margin-top-15" @click="save_general_settings()">Save General Settings</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import EventBus from '../../utils/event-bus';
    import Object from '../../utils/compare_objects';

    const config_description = {
        theme: 'modern',
        menubar:false,
        fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px",
        plugins: 'lists paste',
        paste_as_text: true,
        toolbar1: 'formatselect fontsizeselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ],
    };

    const config_notes = Object.assign({}, config_description);
	export default {
        props: {
            service_providers:{
                type: Array,
                required: true,
            },
            current_service : {
                type: Object,
                required: false,
                default: function () { return {} },
            },
            service_types : {
                type: Array,
                required: true,
            },
            service_levels : {
                type:Array,
                required: true,
            },
            service_notes_log : {
                type:Array,
                required:false,
                default: function () { return [] },
            },
            catchments : {
                type:Object,
                required:false,
                default: function () { return {
                    Postcode: '',
                    Suburbs: [],
                    LGA: []
                } },
            }
        },
        data () {
            return {
                    service_provider_selected : {},
                    service_type_selected: {},
                    service_level_selected: {},
                    lgas : [],
                    lgas_selected : [],
                    suburbs : [],
                    suburbs_selected : [],
                    config_description,
                    config_notes,
                    status:false,
                    initial_general_settings: {},
            }
        },
        computed: {
            show_log_notes() {
                let notes = '';
                notes = this.service_notes_log.map( note_log => {
                    return `<li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col2">
                                            <div class="desc"> ${note_log.note}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> ${note_log.created_at} </div>
                                </div>
                            </li>`;
                        }).join('');
                return notes;
            }
        },
        methods: {
            preselect_data: function() {
                let self = this;
                if(self.current_service) {
                    self.service_providers.forEach(service_provider => {
                        if(service_provider.ServiceProviderId == self.current_service.ServiceProviderId) {
                            return self.service_provider_selected = service_provider;
                        }
                    });
                    self.service_types.forEach(service_type => {
                        if(service_type.ServiceTypelId == self.current_service.ServiceTypeId) {
                            return self.service_type_selected = service_type;
                        }
                    });
                    self.service_levels.forEach(service_level => {
                        if(service_level.ServiceLevelId == self.current_service.ServiceLevelId) {
                            return self.service_level_selected = service_level;
                        }
                    });
                }
            },
            set_initial_general_settings(){
                this.initial_general_settings = Object.assign({}, this.get_general_settings());
            },
            init_catchments: function() {
                let self = this;
                axios.get('/catchment/listLgcs' )
                    .then(function (response) {
                        self.lgas = response.data;
                        if(self.catchments){
                            self.lgas.forEach(lga => {
                                if(self.catchments.LGA.indexOf(lga.id) !== -1 ){
                                    self.lgas_selected.push(lga);
                                }
                            });
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                axios.get('/catchment/listSuburbs' )
                    .then(function (response) {
                        self.suburbs = response.data;
                        if(self.catchments){
                            self.suburbs.forEach(suburb => {
                                if(self.catchments.Suburbs.indexOf(suburb.id) !== -1 ){
                                    self.suburbs_selected.push(suburb);
                                }
                            });
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                // init status;
                if(self.current_service){
                    self.status = false;
                    if(self.current_service.Status == 1){
                        self.status = true;
                    }
                }
            },
            event_on_change_tab() {
                let self = this;
                EventBus.$on('CHANGE_TAB_SETTINGS', function (payLoad) {
                    if(!Object.compare(self.get_general_settings(), self.initial_general_settings)){
                        self.$swal({
                            title: 'Save changes?',
                            text: "Do not miss any modification you have made so far",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#17c4bb',
                            cancelButtonColor: '#e2e5ec',
                            confirmButtonText: 'Yes',
                            allowEscapeKey: false,
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.value) {
                                //Call save method of current tab
                                self.save_general_settings();
                            }
                        });
                    }
                });
            },
            get_general_settings() {
                let self = this;
                return {
                    current_service: Object.assign({}, self.current_service),
                    lga:self.lgas_selected,
                    suburbs:self.suburbs_selected,
                    postcodes: self.catchments.Postcode,
                    service_provider: self.service_provider_selected.ServiceProviderId,
                    service_type:self.service_type_selected.ServiceTypelId,
                    service_level:self.service_level_selected.ServiceLevelId,
                    status: self.status,
                };
            },
            save_general_settings() {
                let self = this;
                if(self.validateGeneralSettings()){
                    $('#contentLoading').modal('show');
                    let general_settings = self.get_general_settings();
                    let url = '/service/general_settings';
                    self.$parent.submit('post',url, general_settings)
                        .then(response => {
                            $('#contentLoading').modal('hide');
                            self.$parent.swal_messages(response.success, response.message);
                            self.initial_general_settings = Object.assign({}, self.get_general_settings());
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
            },
            validateGeneralSettings() {
                let self = this;
                let message = '';
                let result = true;
                if(!self.service_provider_selected.hasOwnProperty('ServiceProviderId')) {
                    message = "Please select a Service Provider";
                }
                if(!self.current_service.ServiceName){
                    message = "Please select a Service Name";
                }
                if(!self.service_type_selected.hasOwnProperty('ServiceTypelId')){
                    message = "Please select a Service Type";
                }
                if(!self.service_level_selected.hasOwnProperty('ServiceLevelId')){
                    message = "Please select a Service Level";
                }
                if(!self.current_service.Wait){
                    message = "Please select a Wait Time";
                }
                if(!self.current_service.Location){
                    message = "Please select a Location";
                }
                if(!self.current_service.Phone){
                    message = "Please select a Phone Number";
                }
                if(!self.current_service.Email){
                    message = "Please select an Email";
                }
                if(!self.current_service.URL){
                    message = "Please select a Website";
                }
                if(!self.current_service.OpenningHrs){
                    message = "Please select a Opening Hours";
                }

                if(message){
                    self.$parent.swal_messages("error", message);
                    result = false;
                }
                return result;

            }


        },
        watch:{

        },
        created() {
            this.init_catchments();
            this.preselect_data();
            this.set_initial_general_settings();
            this.event_on_change_tab();
        },
    }

</script>
