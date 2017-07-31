@extends ('orbit.master')

@section ('content')
<!-- Steps -->
<div class="mt-element-step margin-bottom-20">
  <div class="row step-line">
    <div class="col-xs-2 col-xs-offset-1 mt-step-col first active">
      <div class="mt-step-number bg-white">
        <i class="fa fa-map-marker"></i>
      </div>
      <div class="mt-step-title font-grey-cascade">Location</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-2 mt-step-col">
      <div class="mt-step-number bg-white">
        <i class="fa fa-legal font-grey-cascade"></i>
      </div>
      <div class="mt-step-title font-grey-cascade">Legal Issue</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-2 mt-step-col">
      <div class="mt-step-number bg-white">
        <i class="fa fa-check-square-o font-grey-cascade"></i>
      </div>
      <div class="mt-step-title font-grey-cascade">Client Details</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-2 mt-step-col">
      <div class="mt-step-number bg-white">
        <i class="fa fa-question font-grey-cascade"></i>
      </div>
      <div class="mt-step-title font-grey-cascade">Questions</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-2 mt-step-col last">
      <div class="mt-step-number bg-white">
        <i class="fa fa-list font-grey-cascade"></i>
      </div>
      <div class="mt-step-title font-grey-cascade">Results</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
  </div>
</div>

<!-- Location -->
<div class="row">
  <div class="col-xs-10 col-lg-8 col-xs-offset-1 col-lg-offset-2">
    <div class="portlet light">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject font-green sbold"><i class="fa fa-map-marker font-green"></i>&nbsp; Location</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row">
          <div class="col-xs-12">
              <p>Service providers may limit entry into their service based on where a client works, lives or studies.</p>
          </div>
          <div class="col-xs-12">
            <div class="form-group">
                <label for="single-prepend-text" class="control-label">Select or search for the client's location:</label>
                <div class="input-group select2-bootstrap-prepend">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                              <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </span>

                    <select class="form-control" id="single-prepend-text" name="lga[]">   
                         <option value="0">Search for suburbs, postcodes or councils</option> 
                    </select>
                      
                        <!-- <option>  Search for a suburb, postcode or council    </option> -->
                </div>
            </div>
          </div>
        </div>
      </div>
    </div> 
  </div>
</div> 

<!-- Navigation -->  
<div class="row">
  <div class="col-xs-10 col-lg-8 col-xs-offset-1 col-lg-offset-2"><br>
    <div class="col-xs-4 col-lg-3 pull-right">
      <a href="#" id="next-location" class="btn green-jungle btn-block btn-lg pull-right">
        <span>Next &nbsp;<i class="fa fa-lg fa-angle-right"></i></span> 
      </a>
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
@endsection

@section('scripts')
    <script src="/js/catchments.js?id={{ str_random(6) }}"></script>
@endsection
