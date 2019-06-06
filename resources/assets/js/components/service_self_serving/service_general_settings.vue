<template>
    <div class="form">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-xs-5">
                    <p class="caption-subject font-purple-soft bold uppercase margin-bottom-10">Service Details</p>
                </div>
                <div class="col-xs-7 text-right">
                    <label for="Status"><small>Show this service in results?</small></label>
                    <input type="checkbox" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" id="Status" v-model="current_service.Status">
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
                    :config="config"
                    name="description"/>
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
            <div class="col-sm-12">
                <button type="button" class="btn btn-circle green margin-top-15" @click="save_general_settings()">Save General Settings</button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import EventBus from '../../utils/event-bus';

    const config = {
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
            catchments : {
                type:Object,
                required:false,
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
                    config,
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
            init_catchments: function() {
                let self = this;
                axios.get('/catchment/listLgcs' )
                    .then(function (response) {
                        self.lgas = response.data;
                        if(self.catchments){
                            self.lgas.forEach(lga => {
                                if(self.catchments.LGA.includes(lga.id)){
                                    self.lgas_selected.push(lga);
                                }
                            });
                        }
                    })
                    .catch(function (error) {
                        self.init_catchments();
                        console.log(error);
                    });
                axios.get('/catchment/listSuburbs' )
                    .then(function (response) {
                        self.suburbs = response.data;
                        if(self.catchments){
                            self.suburbs.forEach(suburb => {
                                if(self.catchments.Suburbs.includes(suburb.id)){
                                    self.suburbs_selected.push(suburb);
                                }
                            });
                        }
                    })
                    .catch(function (error) {
                         self.init_catchments();
                        console.log(error);
                    });
            },
            event_on_change_tab() {
                let self = this;
                EventBus.$on('CHANGE_TAB_SETTINGS', function (payLoad) {
                    //self.save_general_settings();
                });
            },
            save_general_settings() {
                let self = this;
                $('#contentLoading').modal('show');
                let general_settings = {
                    current_service: self.current_service,
                    lga:self.lgas_selected,
                    suburbs:self.suburbs_selected,
                    postcodes: self.catchments.Postcode,

                };
                let url = '/service/general_settings';
                this.submit_service_gs('post',url, general_settings)
                .then(response => {
                    $('#contentLoading').modal('hide');
                    this.swal_messages(response.success, response.message);
                })
                .catch(error => {
                    console.log(error);
                });
            },
            submit_service_gs(requestType, url, data) {
                return new Promise((resolve, reject) => {
                    //Do Ajax or Axios request
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest'
                    };
                    axios[requestType](url, data)
                        .then(response => {
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response.data);
                    });
                });
            },
            swal_messages(type, message) {
                this.$swal(type.charAt(0).toUpperCase() + type.slice(1), message, type);
            },


        },
        watch:{

        },
        created() {
            this.init_catchments();
            this.preselect_data();
        },
    }

</script>
