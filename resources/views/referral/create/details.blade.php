@extends ('orbit.master')

@section ('content')
<!-- Steps -->
<div class="mt-element-step margin-bottom-20">
  <div class="row step-line">
    <div class="col-xs-3 mt-step-col first done" style="cursor: pointer;" onclick="window.location='./location';">
      <div class="mt-step-number bg-white">
        <i class="fa fa-map-marker"></i>
      </div>
      <div class="mt-step-title font-grey-cascade">Location</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-3 mt-step-col done" style="cursor: pointer;" onclick="window.location='./legal_issue';">
      <div class="mt-step-number bg-white">
        <i class="fa fa-legal"></i>
      </div>
      <div class="mt-step-title font-grey-cascade">Legal Issue</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-3 mt-step-col active">
      <div class="mt-step-number bg-white">
        <i class="fa fa-check-square-o"></i>
      </div>
      <div class="mt-step-title font-grey-cascade">Client Details</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-3 mt-step-col last">
      <div class="mt-step-number bg-white">
        <i class="fa fa-question font-grey-cascade"></i>
      </div>
      <div class="mt-step-title font-grey-cascade">Matching Questions</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
  </div>
</div>

<!-- Details -->
<div class="row"> 
  <div class="col-xs-10 col-xs-offset-1">
    <div class="portlet light">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject font-green sbold"><i class="icon-user font-green"></i> &nbsp;Client Details</span> 
        </div>
        <div class="pull-right caption">
          <small># of services ( {{ $service_qty }} )</small>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row">
          <div class="col-xs-12">
            <p>Below are the vulnerability criteria that determine possible entry into services that match your location and legal issue. Select any that may apply to the client so that we can match their personal circumstances to services catering specifically for their situation.</p>
          </div>
          <div class="col-xs-12">
            <div class="form-group">
              <div class="mt-checkbox-list">
              @foreach($vulnertability_questions as $vulnerability_question)                 
                <label class="mt-checkbox mt-checkbox-outline col-md-6"> 
                  {{ ( $vulnerability_question["QuestionLabel"] != '' ? $vulnerability_question["QuestionLabel"]: $vulnerability_question["QuestionName"] )  }} 
                  <i class="fa fa-info-circle tooltips" aria-hidden="true" data-container="body" data-placement="right" data-original-title="{{ $vulnerability_question['QuestionName']  }}"></i>
                  <input type="checkbox" class="form-control" name="vulnerability" id="{{ $vulnerability_question['QuestionId'] }}" {{ ( isset( $current_vulnerabilities ) && in_array($vulnerability_question['QuestionId'], $current_vulnerabilities) ? 'checked' : '' ) }}>
                  <span></span>
                </label>
              @endforeach

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
  <div class="col-xs-10 col-xs-offset-1"><br>
    <div class="col-xs-4 col-lg-3 pull-left">
      <a href="#" class="btn grey-mint btn-block btn-lg pull-left" id="back"><span><i class="fa fa-lg fa-angle-left"></i>&nbsp; Back</span></a>
    </div>
    <div class="col-xs-4 col-lg-3 pull-right">
      <a href="#" id="next-eligibility" class="btn green-jungle btn-block btn-lg pull-right"><span>Next &nbsp;<i class="fa fa-lg fa-angle-right"></i></span></a>
    </div>
  </div>
  <div class="col-xs-10 col-lg-8 col-xs-offset-1 col-lg-offset-2"><br></div>
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
    <script src="/js/eligibility.js?id={{ str_random(6) }}"></script>
@endsection