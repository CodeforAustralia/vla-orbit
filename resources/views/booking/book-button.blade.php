<div class="booking-area">        
    <div class="row availability hidden">
        <div class="col-xs-12 padding-bottom-20">
            <div class="form-group">
                <div class="col-xs-12">
                    <label>Date of appointment: choose from dates marked green</label>                
                    <input type="text" class="form-control input-medium" id="booking-date" name="booking-date" required>
                </div>
            </div>
        </div>
    </div>

    <div class="row availability hidden">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="col-xs-12">
                    <label>Available Times: <small>choose from dates marked green</small></labe>
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
</div>
