
    <!-- Modal Start -->     
    <div class="modal fade" id="bookingInfo" tabindex="-1" role="dialog" aria-labelledby="bookingInfo" data-backdrop="static" data-keyboard="false" aria-hidden="false" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">                
                    <button type="button" class="close close-booking-edit" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h5 class="modal-title" id="SelectMatchLabel"><strong>Booking #<span id="bookingRef"></span></strong></h5>
                </div>

                <div class="modal-body">
                    <div class="container-fluid booking-information">
                        <div class="row" id='clientInformation'>
                            <div class="col-sm-6">
                                <h4><strong> Client </strong></h4>
                            </div>
                            <div class="col-sm-6">
                                <i class="fa fa-print pull-right btn" id="printBooking"></i>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>First name: </strong></label>                                
                                <a href="javascript:;" id="bookingFirstName" data-type="text" data-original-title="Enter First name"></a>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Last name: </strong></label>                                
                                <a href="javascript:;" id="bookingLastName" data-type="text" data-original-title="Enter Last name"></a>
                            </div>
                            
                            <div class="col-sm-12">
                                &nbsp;
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Phone number: </strong></label>                                 
                                <a href="javascript:;" id="bookingPhone" data-type="text" data-original-title="Enter Phone number"></a>
                            </div>
                            
                            <div class="col-sm-12 hide">
                                <label class="col-sm-5"><strong>Safe to call: </strong></label>
                                <span id="IsSafeCall"></span>
                            </div>

                            <div class="col-sm-12 hide">
                                <label class="col-sm-5"><strong>Safe to leave a message: </strong></label>
                                <span id="IsSafeLeaveMessage"></span>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>SMS Reminder: </strong></label>
                                <span id="IsSafeSMS"></span>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Instructions to re contact: </strong></label>
                                <span id="ContactInstructions"></span>
                            </div>
                            
                            <div class="col-sm-12">
                                &nbsp;
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Interpreter Language: </strong></label>
                                <span id="bookingIntLanguage"></span>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Complex needs: </strong></label>
                                <span id="IsComplex"></span>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>CIR Number: </strong></label>                                
                                <a href="javascript:;" id="bookingCIRNumber" data-type="text" data-original-title="Enter CIR number"></a>
                            </div>                      
                        </div>
                        <hr>
                        <div class="row" id="bookingInformation">
                            <div class="col-sm-6">
                                <h4><strong> Appointment </strong></h4>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Service Provider: </strong></label>
                                <span id="bookingSPName"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Service Name: </strong></label>
                                <span id="bookingTitle"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Time of appointment: </strong></label>
                                <a id="bookingTime" class="editable editable-click edit-booking"></a>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Appointment Description: </strong></label>
                                <div id="bookingDescription" class="col-sm-7 padding-0" data-type="wysihtml5"></div>
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
                        
                        <hr>
                        <div class="row" id="extraInformation">
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>SMS status: </strong></label>
                                <span id="sentStatus"></span>
                                <a href="#" class="btn btn-xs green remind-booking pull-right">Send Reminder</a>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Arrival status: </strong></label>
                                
                                <select class="form-control input-small booking-status input-sm">
                                    <option value="1" class="bg-white bg-font-white">Pending</option>
                                    <option value="2" class="bg-white font-green-jungle">Arrived</option>
                                    <option value="3" class="bg-white font-red">No Show</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row pull-right">                            
                            <div class="col-sm-12">
                                <a href="#" class="btn btn-xs btn-danger" id="delete-booking">Delete</a>                   
                                <a href="#" class="btn btn-xs btn-outline dark close-booking-edit" data-dismiss="modal" >Close</a>
                            </div>
                        </div>
                    </div>
                    <div class="hidden booking-edit">
                        <div class="row availability hidden">
                            <div class="col-xs-12 padding-bottom-20">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <label>Date:</label>                
                                        <input type="text" class="form-control input-medium" id="booking-date" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row availability hidden">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <label>Available Times:</label>
                                        <div class="mt-radio-list" id="time-options">
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        </div>

                        <div class="row hidden" id="no-dates-availables">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h3>Sorry there are no dates available for this service</h3>
                                    </div>
                                </div>   
                            </div>
                        </div>

                        <div class="row hidden" id="loading">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="col-xs-12">                
                                        <i class="fa fa-spinner fa-spin font-green-jungle" style="font-size:24px"></i>
                                    </div>
                                </div>   
                            </div>
                        </div>
                        <button id="update-booking" class="btn green">Update</button>
                    </div>
                </div>
                <div class="modal-footer">                    
                    <div class="col-sm-12 text-center">
                        <small>Created By: <span id="createdBy"></span></small>
                    </div>
                </div>
            </div>
        </div><!-- Modal Dialogue Close-->
    </div><!-- Modal Fade Close-->