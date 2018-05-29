@extends ('orbit.master')

@section ('content')
<!-- Steps -->
<div class="mt-element-step margin-bottom-20">
  <div class="row step-line">
    <div class="col-xs-3 col-xs-offset-1 mt-step-col first active">
      <div class="mt-step-number bg-white">
        <i class="fa fa-search"></i>
      </div>
      <div class="mt-step-title font-grey-cascade hidden-xs">Search</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-3 mt-step-col">
      <div class="mt-step-number bg-white">
        <i class="fa fa-check-square-o font-grey-cascade"></i>
      </div>
      <div class="mt-step-title font-grey-cascade hidden-xs">Refine</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-3 mt-step-col last">
      <div class="mt-step-number bg-white">
        <i class="fa fa-list font-grey-cascade"></i>
      </div>
      <div class="mt-step-title font-grey-cascade hidden-xs">Matches</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
  </div>
</div>

<!-- Location -->
<div class="row search-page">
  <div class="col-xs-11 col-lg-10 col-xs-offset-1 col-lg-offset-1">
    <div class="portlet light">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject font-green sbold"><i class="fa fa-search font-green"></i>&nbsp; Search</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row">
          <div class="col-xs-12">
              <p>Find a service by selecting the legal issue that best matches the client’s situation and put in the location of where the client lives.</p>              
          </div>
          <div class="col-xs-12">
              
              <div class="form-group">

                <div class="input-group select2-bootstrap col-xs-12 col-sm-6 legal_issue">
                  <select id="single" class="form-control select2" name="legal_issue">
                      <option> </option>
                  </select>
                </div>

                <div class="input-group select2-bootstrap-prepend col-xs-12 col-sm-6 location">
                    
                    <select class="form-control" id="single-prepend-text" name="lga[]">   
                         <!-- option value="0" style="color: #999;">Search for suburbs, postcodes or councils</option --> 
                        <option> </option>
                    </select>                      
                </div>

              </div>

          </div>
        </div>
        <div class="panel panel-default" id="referral_panel">
          <div class="panel-heading" id="referral_panel_heading">
            <div class="tools">
              <a class="accordion-toggle" id="advance-options" data-toggle="collapse" data-parent="#accordion1" href="#collapse"> Advanced Search </a>
            </div>
          </div>          
          <div id="collapse" class="panel-collapse collapse">
              <div class="panel-body">                  
                  <div class="form-group col-sm-12">
                    <div class='mt-checkbox-inline'> 
                        <label >Include in results:</label> &nbsp;&nbsp;                     
                        <label class="mt-checkbox"> <input type="checkbox" id="select_all">All<span></span></label>
                        <label class="mt-checkbox"><input id='referral_CLC' type="checkbox" name="check[]" class='checkbox' value="2"/>CLC<span></span></label>
                        <label class="mt-checkbox"><input id='referral_VLA' type="checkbox" name="check[]" class='checkbox' value="3,4"/>VLA<span></span></label>
                        <label class="mt-checkbox"><input id='referral_NLP' type="checkbox" name="check[]" class='checkbox' value="1"/>Non-legal<span></span></label>
                        <label class="mt-checkbox"><input id='referral_PP' type="checkbox" name="check[]" class='checkbox' value="5,6"/>Private Practitioner<span></span></label>
                    </div>
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
  <div class="col-xs-11 col-lg-10 col-xs-offset-1 col-lg-offset-1"><br>
    <div class="col-xs-6 col-sm-4 col-lg-3 pull-right">
      <a href="#" id="next-page" class="btn green-jungle btn-block btn-lg pull-right">
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
    <script src="/js/search.js?id={{ str_random(6) }}"></script>
@endsection

@section('inline-scripts')
  $(document).ready(function() {
  });
@endsection