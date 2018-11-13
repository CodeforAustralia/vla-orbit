
    <!-- Modal Start -->
    <div class="modal fade" id="bookingInfo" tabindex="-1" role="dialog" aria-labelledby="bookingInfo" data-backdrop="static" data-keyboard="false" aria-hidden="false" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-booking-edit" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h5 class="modal-title" id="SelectMatchLabel"><strong>Booking #<span id="bookingRef" v-html="current_booking.id"></span></strong></h5>
                </div>

                <div class="modal-body">
                    <div class="container-fluid booking-information">
                        <div class="row" id='clientInformation' v-show="!show_date && !show_sms">
                            <div class="col-sm-6">
                                <h4><strong> Client </strong></h4>
                            </div>
                            <div class="col-sm-6">
                                <i class="fa fa-print pull-right btn" id="printBooking"></i>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>First name: </strong></label>
                                <a href="javascript:;"
                                    class="col-sm-5"
                                    id="bookingFirstName"
                                    data-type="text"
                                    v-show="!showField('first_name')"
                                    v-html="(current_booking.client ? current_booking.client.first_name : '')"
                                    @click="enableEditing(current_booking.client ? current_booking.client.first_name : '', 'first_name')"></a>
                                <input  v-model="temp_value" class="form-control input-small col-sm-5" v-show="showField('first_name')"/>
                                <button class='btn blue editable-submit col-sm-1'
                                        v-show="showField('first_name')"
                                        @click="updateBookingField('client.first_name')">
                                        <i class="fa fa-check"></i>
                                </button>
                                <button class="btn default editable-cancel col-sm-1" @click="disableEditing" v-show="showField('first_name')"> <i class="fa fa-times"></i> </button>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Last name: </strong></label>
                                <a href="javascript:;"
                                class="col-sm-5"
                                id="bookingLastName"
                                data-type="text"
                                v-show="!showField('last_name')"
                                v-html="(current_booking.client ? current_booking.client.last_name : '' )"
                                @click="enableEditing(current_booking.client ? current_booking.client.last_name : '','last_name')"></a>
                                <input  v-model="temp_value" class="form-control input-small col-sm-5" v-show="showField('last_name')"/>
                                <button class='btn blue editable-submit col-sm-1'
                                        v-show="showField('last_name')"
                                        @click="updateBookingField('client.last_name')">
                                        <i class="fa fa-check"></i>
                                </button>
                                <button class="btn default editable-cancel col-sm-1" @click="disableEditing" v-show="showField('last_name')"> <i class="fa fa-times"></i> </button>

                            </div>

                            <div class="col-sm-12">
                                &nbsp;
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Phone number: </strong></label>
                                <a href="javascript:;"
                                class="col-sm-5"
                                id="bookingPhone"
                                data-type="text"
                                v-show="!showField('contact')"
                                v-html="(current_booking.client ? current_booking.client.contact : '' )"
                                @click="enableEditing(current_booking.client ? current_booking.client.contact : '','contact')"></a>
                                <input  v-model="temp_value" class="form-control input-small col-sm-5" v-show="showField('contact')"/>
                                <button class='btn blue editable-submit col-sm-1'
                                        v-show="showField('contact')"
                                        @click="updateBookingField('client.contact')">
                                        <i class="fa fa-check"></i>
                                </button>
                                <button class="btn default editable-cancel col-sm-1" @click="disableEditing" v-show="showField('contact')"> <i class="fa fa-times"></i> </button>

                            </div>

                            <div class="col-sm-12 hide">
                                <label class="col-sm-5"><strong>Safe to call: </strong></label>
                                <span id="IsSafeCall" class="col-sm-5" v-html="(current_booking.data ? (current_booking.data.IsSafeCall ? 'Yes' : 'No') : '' )"></span>
                            </div>

                            <div class="col-sm-12 hide">
                                <label class="col-sm-5"><strong>Safe to leave a message: </strong></label>
                                <span id="IsSafeLeaveMessage" class="col-sm-5" v-html="(current_booking.data ? (current_booking.data.IsSafeLeaveMessage ? 'Yes' : 'No') : '' )"></span>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>SMS Reminder: </strong></label>
                                <span id="IsSafeSMS" class="col-sm-5" v-html="(current_booking.data ? (current_booking.data.IsSafeSMS ? 'Yes' : 'No') : '' )"></span>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Instructions re contact: </strong></label>
                                <span id="ContactInstructions" class="col-sm-5" v-html="(current_booking.data ? (current_booking.data.ContactInstructions ? 'Yes' : 'No') : '' )"></span>
                            </div>

                            <div class="col-sm-12">
                                &nbsp;
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Interpreter Language: </strong></label>
                                <span id="bookingIntLanguage" class="col-sm-5" v-html="current_booking.int_language"></span>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Complex needs: </strong></label>
                                <span id="IsComplex" class="col-sm-5" v-html="(current_booking.data ? (current_booking.data.IsComplex ? 'Yes' : 'No') : '' )"></span>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>CIR Number: </strong></label>
                                <a href="javascript:;"
                                class="col-sm-5"
                                id="bookingCIRNumber"
                                data-type="text"
                                v-show="!showField('CIRNumber')"
                                v-html="(current_booking.data && current_booking.data.CIRNumber ? current_booking.data.CIRNumber : 'N/P' )"
                                @click="enableEditing(current_booking.data ? current_booking.data.CIRNumber : '','CIRNumber')"></a>
                                <input  v-model="temp_value" class="form-control input-small col-sm-5" v-show="showField('CIRNumber')"/>
                                <button class='btn blue editable-submit col-sm-1'
                                        v-show="showField('CIRNumber')"
                                        @click="updateBookingField('data.CIRNumber')">
                                        <i class="fa fa-check"></i>
                                </button>
                                <button class="btn default editable-cancel col-sm-1" @click="disableEditing" v-show="showField('CIRNumber')"> <i class="fa fa-times"></i> </button>

                            </div>
                        </div>
                        <hr v-show="!show_date && !show_sms">
                        <div class="row" id="bookingInformation" v-show="!show_date && !show_sms">
                            <div class="col-sm-6">
                                <h4><strong> Appointment </strong></h4>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Service Provider: </strong></label>
                                <span  class='col-sm-5' id="bookingSPName" v-html="(current_booking.orbit_service ? current_booking.orbit_service.ServiceProviderName : '' )"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Service Name: </strong></label>
                                <span class='col-sm-5' id="bookingTitle" v-html="(current_booking.orbit_service ? current_booking.orbit_service.ServiceName : '')"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Time of appointment: </strong></label>
                                <a id="bookingTime" @click="initDatePicker" class="editable editable-click edit-booking col-sm-5" v-html="current_booking.date + ' at ' + current_booking.booking_time"></a>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Appointment Description: </strong></label>
                                <div class="col-sm-5"
                                id="bookingdescription"
                                data-type="text"
                                v-show="!showField('comment')"
                                v-html="(current_booking.comment ? current_booking.comment : 'N/P' )"
                                @click="enableEditing(current_booking.comment ? current_booking.comment : '','comment')"></div>
                                <textarea v-model="temp_value" v-show="showField('comment')" rows="5" class="form-control col-sm-5" id="Desc"  name="Desc"></textarea>
                                <button class='btn blue editable-submit col-sm-1'
                                        v-show="showField('comment')"
                                        @click="updateBookingField('comment')">
                                        <i class="fa fa-check"></i>
                                </button>
                                <button class="btn default editable-cancel col-sm-1" @click="disableEditing" v-show="showField('comment')"> <i class="fa fa-times"></i> </button>
                            </div>
                            <br>
                            <div class="col-sm-12">
                                <label class="col-sm-5">&nbsp;</label>

                                    <div class="tab-pane" id="booking_document_tab">
                                        <div class="scroller" style="height: auto;" data-always-visible="1" data-rail-visible1="1">
                                            <ul class="feeds">

                                            </ul>
                                        </div>
                                    </div>
                            </div>

                        </div>

                        <hr v-show="!show_date && !show_sms">
                        <div class="row" id="extraInformation" v-show="!show_date && !show_sms">
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>SMS status: </strong></label>
                                <span id="sentStatus"></span>
                                <a href="#" class="btn btn-xs green pull-right" @click="getCurrentServiceTemplate">Send SMS</a>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Arrival status: </strong></label>
                                <multiselect
                                class="input-small col-sm-5"
                                v-model="current_booking.bookingstatus"
                                label="name"
                                key="id"
                                id="booking_status_select"
                                track-by="name"
                                placeholder="Select"
                                open-direction="bottom"
                                :options="booking_status_options"
                                :multiple="false"
                                :searchable="false"
                                :close-on-select="true"
                                :show-no-results="false"
                                :show-labels="false"
                                :allow-empty="false"
                                @input="updateBookingField('booking_status_id')"
                                >
                                </multiselect>
                            </div>
                        </div>
                        <br v-show="!show_date">
                        <div class="row pull-right" v-show="!show_date && !show_sms">
                            <div class="col-sm-12">
                                <a @click="deleteBooking" class="btn btn-xs btn-danger" id="delete-booking">Delete</a>
                                <a href="#" class="btn btn-xs btn-outline dark close-booking-edit" data-dismiss="modal" >Close</a>
                            </div>
                        </div>

                        <div class="row" id="bookingdate" v-show="show_date">
                            <div class="col-sm-6">
                                <h4><strong> Change Appointment Date </strong></h4>
                            </div>
                            <div class="col-sm-12">
                                <label>Date of appointment: <small>choose from dates marked green</small></label>
                                <input type="text" class="form-control input-medium" id="booking-date" v-model='selected_date' name="booking-date">
                            </div>
                            <div class="col-sm-12">
                                <label>Available Times:</label>
                                <div class="mt-radio-list">
                                    <label class="mt-radio mt-radio-outline" v-for= "time in available_times" :key="time.start_time" >
                                            <input type="radio" id='time' :value="time" v-model="hour">
                                            @{{ time.text }}<span></span><br>
                                    </label>
                                </div>

                            </div>
                            <div class="col-sm-12">
                                <button id="update-booking" @click="updateBookingDate" class="btn green">Update</button>
                                <button id="cancel" @click="show_date = false" class="btn blue">Cancel</button>
                            </div>
                        </div>
                        <div class="row" id="bookingSMS" v-show="show_sms">
                            <div class="col-sm-6">
                                <h4><strong> Edit SMS here if required: </strong></h4>
                            </div>
                            <div class="col-sm-12">
                                <textarea class="form-control" rows='5' id="booking-sms" v-model='sms' name="booking-sms"></textarea>
                            </div>
                            <div class="col-sm-12">
                                <button id="send" @click="sendSMSReminder" class="btn green">Send</button>
                                <button id="cancel" @click="show_sms = false" class="btn blue">Cancel</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 text-center">
                        <small>Created By: <span id="createdBy" v-html="(current_booking.data ? (current_booking.data.CreatedBy ? current_booking.data.CreatedBy : '') : '' )"></span></small>
                    </div>
                </div>
            </div>
        </div><!-- Modal Dialogue Close-->
    </div><!-- Modal Fade Close-->