@extends ('orbit.master')

@section ('content')
<!-- Questions -->
<div class="row">
    <div class="col-xs-10 col-lg-8 col-xs-offset-1 col-lg-offset-2">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-green sbold"><i class="icon-question font-green"></i>&nbsp; New Booking</span>
                </div>
            </div>
            <div class="portlet-body form">
                
                <form role="form">
                    <h4 class="padding-top-10 padding-bottom-10">Service</h4>
                    
                    <div class="row">
                        <div class="col-xs-12 padding-bottom-20">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Service Provider:</label>
                                </div>
                                <div class="col-xs-8">
                                    <select class="form-control">
                                        <option selected disabled> </option>
                                        <option>Criminal Law Melbourne</option>
                                        <option>VLA Ringwood</option>
                                        <option>VLA Sunshine</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="col-xs-12 padding-bottom-20">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Service:</label>  
                                </div>
                                <div class="col-xs-12 col-sm-8">
                                    <select class="form-control" data-toggle="modal" data-target="#EligibilityConfirm">
                                        <option selected disabled> </option>
                                        <option>Legal Advice Appointment - Criminal Law</option>
                                        <option>Sunshine Magistrates Court Duty Lawyer Service</option>
                                        <option>Werribee Magistrates Court Duty Lawyer Service</option>
                                    </select>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <button type="button" class="btn btn-block dark btn-outline" data-toggle="modal" data-target="#EligibilityConfirm">View Service Details</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>  
                    <h4 class="padding-top-10 padding-bottom-10">Appointment</h4>
                    
                    <div class="row">
                        <div class="col-xs-12 padding-bottom-20">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Date:</label>
                                    <input class="form-control date-picker input-medium" placeholder="MM/DD/YYYY"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Available Times:</label>
                                    <div class="mt-radio-list">
                                        <label class="mt-radio mt-radio-outline">
                                            <input type="radio" name="times" id="Option1"> 11:30am - 12:00pm
                                            <span></span>
                                        </label>
                                        <label class="mt-radio mt-radio-outline">
                                            <input type="radio" name="times" id="Option2"> 2:00pm - 2:30pm
                                            <span></span>
                                        </label>
                                        <label class="mt-radio mt-radio-outline">
                                            <input type="radio" name="times" id="Option3"> 2:30pm - 3:00pm
                                            <span></span>
                                        </label>
                                        <label class="mt-radio mt-radio-outline">
                                            <input type="radio" name="times" id="Option4"> 4:00pm - 4:30pm
                                            <span></span>
                                        </label>                            
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                    
                    <hr>
                    <h4 class="padding-top-10 padding-bottom-10">Client Details</h4>
                    
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="col-xs-12 padding-bottom-10">
                                    <label>First Name:</label>
                                    <input type="text" class="form-control input-large" placeholder="Jane"> 
                                </div>
                            </div>
                        </div>
                    </div>
                            
                   <div class="row">
                        <div class="col-xs-12">        
                            <div class="form-group">
                                <div class="col-xs-12 padding-bottom-20">
                                    <label>Last Name:</label>
                                    <input type="text" class="form-control input-large" placeholder="Smith"> 
                                </div>
                            </div>
                        </div>
                    </div>

                   <div class="row">
                        <div class="col-xs-12">                            
                            <div class="form-group">
                                <div class="col-xs-12 padding-bottom-10">
                                    <label>Email:</label>
                                    <input type="text" class="form-control input-large" placeholder="janesmith@gmail.com"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    
                   <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Is it safe to contact this client by email?</label>
                                    <div class="mt-radio-inline padding-left-20">
                                        <label class="mt-radio mt-radio-outline">
                                            <input type="radio" name="emailpermission" id="emailpermission" value="Yes">Yes<span></span>
                                        </label>
                                        <label class="mt-radio mt-radio-outline">
                                            <input type="radio" name="emailpermission" id="emailpermission" value="No">No<span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    
                   <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="col-xs-12 padding-bottom-10">
                                    <label>Phone Number:</label>
                                    <input type="text" class="form-control input-large" placeholder="0400 000 000"> 
                                </div>
                            </div>
                        </div>
                    </div>
                   
                   <div class="row">
                        <div class="col-xs-12">                            
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Is it safe to contact this client by phone and SMS?</label>
                                    <div class="mt-radio-inline padding-left-20">
                                        <label class="mt-radio mt-radio-outline">
                                            <input type="radio" name="phonepermission" id="phonepermission" value="Yes">Yes<span></span>
                                        </label>
                                        <label class="mt-radio mt-radio-outline">
                                            <input type="radio" name="phonepermission" id="phonepermission" value="No">No<span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-xs-12 padding-top-10 padding-bottom-20">
                            <a href=" " class="btn green-jungle btn-block btn-lg"><span>Make Booking</span></a>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
   
<!-- Bottom Padding -->
<div class="row">
    <div class="col-xs-12">
        <br>
        <br>
    </div>
</div>
    <!-- END PAGE HEADER-->
    
        <!-- Modal Start -->     
        
        

                                    
                                    
<!-- Confirm Eligibility Modal -->                                        
    <div class="modal fade in" id="EligibilityConfirm" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-hidden="false" role="dialog" aria-labelledby="EligibilityConfirm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title" id="SelectMatchLabel">Confirm Service Eligibility</h4>
                </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <p>Service Guidelines Go Here</p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6">
                    <button type="button" data-dismiss="modal" class="btn btn-block btn-outline dark">Cancel</button>
                </div>
                <div class="col-sm-6">
                    <button type="button" data-dismiss="modal" class="btn btn-block green-jungle">Confirm Client Eligibility</button>
                </div>
        </div>
    </div>
    
<!-- Confirm Eligibility Modal -->                                        
    <div class="modal fade in" id="EligibilityConfirm" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-hidden="false" role="dialog" aria-labelledby="EligibilityConfirm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title" id="SelectMatchLabel">Confirm Service Eligibility</h4>
                </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <p>Details, location and eligibility </p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6">
                    <button type="button" data-dismiss="modal" class="btn btn-block btn-outline dark">Cancel</button>
                </div>
                <div class="col-sm-6">
                    <button type="button" data-dismiss="modal" class="btn btn-block green-jungle">Confirm Client Eligibility</button>
                </div>
        </div>
    </div>    

@endsection