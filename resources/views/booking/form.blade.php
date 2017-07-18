
<form role="form" method="POST" action="/booking">

    {{ csrf_field() }}

    <h4 class="padding-top-10 padding-bottom-10">Service</h4>
    
    <div class="row">
        <div class="col-xs-12 padding-bottom-20">
            <div class="form-group">
                <div class="col-xs-12">
                    <label>Service Provider:</label>
                </div>
                <div class="col-xs-8">
                    <select class="form-control" id="service_provider_id" name="service_provider_id">                                
                        @foreach($service_providers as $service_provider)
                        	@if( in_array( $service_provider['ServiceProviderId'], [119, 121, 106, 47] ) )
                            <option value="{{ $service_provider['ServiceProviderId'] }}"> {{ $service_provider['ServiceProviderName'] }} </option>
                            @endif
                        @endforeach
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
                    <select class="form-control" id="sp_services" name="ServiceId" required>
                        <option> </option>
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

    @include ('booking.book-button')                    
    
    <hr>
    <h4 class="padding-top-10 padding-bottom-10">Client Details</h4>
    
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-10">
                    <label>First Name:</label>
                    <input type="text" class="form-control input-large" placeholder="Jane" name="client[FirstName]" required> 
                </div>
            </div>
        </div>
    </div>
            
   <div class="row">
        <div class="col-xs-12">        
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-20">
                    <label>Last Name:</label>
                    <input type="text" class="form-control input-large" placeholder="Smith" name="client[LastName]" required> 
                </div>
            </div>
        </div>
    </div>

   <div class="row">
        <div class="col-xs-12">                            
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-10">
                    <label>Email:</label>
                    <input type="text" class="form-control input-large" placeholder="janesmith@gmail.com" name="client[ClientEmail]" id="email"> 
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
                    <input type="text" class="form-control input-large" placeholder="0400 000 000" name="client[Mobile]" id="mobile"> 
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

    <hr>
    <h4 class="padding-top-10 padding-bottom-10">Booking Details</h4>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-10">
                    <label>Description:</label>
                    <textarea class="form-control" rows="5" class="form-control" id="Desc" name="Desc" required></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-10">
                    <label>Language:</label>
                    <select class="form-control" id="Language" name="Language">    
                        @include( 'booking.language' )
                    </select>                    
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-10">
                    <label>Is there risk of violence? :</label>
                    <div class="mt-radio-inline">
                        <label class="mt-radio">
                            <input type="radio" name="Safe" value="true"> Yes
                            <span></span>
                        </label>
                        <label class="mt-radio">
                            <input type="radio" name="Safe" value="false" required> No
                            <span></span>
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
                    <label>CIR Number :</label>
                    <input type="text" class="form-control input-large" name="CIRNumber" id="CIRNumber">                 
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 padding-top-10 padding-bottom-20">
            <button type="submit" class="btn green-jungle btn-block btn-lg">Make Booking</button>
        </div>
    </div>
</form>
