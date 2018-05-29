@extends ('orbit.master')

@section ('content')
<!-- Steps -->
<div class="mt-element-step margin-bottom-20">
  <div class="row step-line">
    <a class="col-xs-3 col-xs-offset-1 mt-step-col first done" style="text-decoration: none;" href="/referral/create/location/{{ '?ca_id=' . session('ca_id') . '&mt_id=' . session('mt_id') . '&filters=' . session('filters') }}">
      <div class="mt-step-number bg-white">
        <i class="fa fa-search"></i>
      </div>
      <div class="mt-step-title font-grey-cascade hidden-xs">Search</div>
      <div class="mt-step-content"></div>
    </a>
    <div class="col-xs-3 mt-step-col active">
      <div class="mt-step-number bg-white">
        <i class="fa fa-check-square-o"></i>
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

<!-- Details -->
<div class="row details-page"> 
  <div class="col-xs-11 col-lg-10 col-xs-offset-1 col-lg-offset-1">
    <div class="portlet light">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject font-green sbold"><i class="fa fa-check-square-o font-green"></i> &nbsp;Eligibility</span> 
        </div>
        <div class="pull-right caption">
          <small><span class="hidden-xs">{{ $service_qty }} Services Matched</span><span class="visible-xs-block">{{ $service_qty }} Matches</span></small>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row">
          <div class="col-xs-12">
            
              @if( count( $vulnertability_questions ) == 0 )

                <h4>No client details required please click ‘Next’</h4>

              @else

                <p>Select any of the below options that apply to the client: <span class="pull-right font-green" id="show-help">Show help</span></p>
              
              @endif
          </div>


          @foreach( $question_groups_in_questions as $question_group )
          <div class="col-xs-12 col-sm-4">
            <div class="form-group">

              <h3>{{ $question_group['GroupName'] }}</h3>
              @foreach( $question_group['children'] as $question_group_children )
              
                <div class="mt-checkbox-list">
                  <h4>{{ $question_group_children['question_groups']['GroupName'] }}</h4>
                
                    @foreach( $question_group_children['questions'] as $vulnerability_question )

                      <label class="mt-checkbox mt-checkbox-outline"> 
                        {{ ( $vulnerability_question["QuestionLabel"] != '' ? $vulnerability_question["QuestionLabel"]: $vulnerability_question["QuestionName"] )  }} 
                        
                        <input type="checkbox" class="form-control" name="vulnerability" id="{{ $vulnerability_question['QuestionId'] }}" {{ ( isset( $current_vulnerabilities ) && in_array($vulnerability_question['QuestionId'], $current_vulnerabilities) ? 'checked' : '' ) }}>
                        <span></span>
                      </label>

                      @if( $vulnerability_question["QuestionLabel"] != '' && $vulnerability_question["QuestionLabel"] != $vulnerability_question["QuestionName"])
                        <small class="elibigility_label">{{ $vulnerability_question['QuestionName']  }}</small>
                      @endif

                    @endforeach

                </div>
              @endforeach
            </div>
              
          </div>
          @endforeach

        </div>
      </div>
    </div> 
  </div>
</div>  
      
<!-- Navigation -->
<div class="row">
  <div class="col-xs-10 col-xs-offset-1"><br>
    <div class="col-xs-6 col-sm-4 col-lg-3 pull-left">
      <a href="#" class="btn grey-mint btn-block btn-lg pull-left" id="back"><span><i class="fa fa-lg fa-angle-left"></i>&nbsp; Back</span></a>
    </div>
    <div class="col-xs-6 col-sm-4 col-lg-3 pull-right">
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
