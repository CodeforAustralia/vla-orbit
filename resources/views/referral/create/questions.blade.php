@extends ('orbit.master')

@section ('content')
<!-- Steps -->
<div class="mt-element-step margin-bottom-20">
  <div class="row step-line">
    <a class="col-xs-2 col-xs-offset-1 mt-step-col first done" style="text-decoration: none;" href="/referral/create/location">
      <div class="mt-step-number bg-white">
        <i class="fa fa-map-marker"></i>
      </div>
      <div class="mt-step-title font-grey-cascade hidden-xs">Location</div>
      <div class="mt-step-content"></div>
    </a>
    <a class="col-xs-2 mt-step-col done" style="text-decoration: none;" href="/referral/create/legal_issue/{{ '?ca_id=' . session('ca_id')  }}">
      <div class="mt-step-number bg-white">
        <i class="fa fa-legal"></i>
      </div>
      <div class="mt-step-title font-grey-cascade hidden-xs">Legal Issue</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </a>
    <a class="col-xs-2 mt-step-col done" style="text-decoration: none;" href="/referral/create/details/{{ '?ca_id=' . session('ca_id') . '&mt_id=' .  session('mt_id')  }}">
      <div class="mt-step-number bg-white">
        <i class="fa fa-check-square-o"></i>
      </div>
      <div class="mt-step-title font-grey-cascade hidden-xs">Eligibility</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </a>
    <div class="col-xs-2 mt-step-col active">
      <div class="mt-step-number bg-white">
        <i class="fa fa-question"></i>
      </div>
      <div class="mt-step-title font-grey-cascade hidden-xs">Questions</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-2 mt-step-col last">
      <div class="mt-step-number bg-white">
        <i class="fa fa-list font-grey-cascade"></i>
      </div>
      <div class="mt-step-title font-grey-cascade hidden-xs">Matches</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
  </div>
</div>
<!-- Questions -->
<div class="row">
  <div class="col-xs-10 col-lg-8 col-xs-offset-1 col-lg-offset-2">
    <form role="form" id="form_answers" action="/referral/create/result" method="POST" >
      <div class="portlet light">
        <div class="portlet-title">
          <div class="caption">
            <span class="caption-subject font-green sbold"><i class="icon-question font-green"></i>&nbsp; Questions</span>
          </div>
          <div class="pull-right caption">
              <small>{{ $service_qty }} Services Matched</small>       
          </div>
        </div>
        <div class="portlet-body">
            {{ csrf_field() }}
            <div class="form-body">
             
              <input type="number" class="form-control hidden" placeholder="" name="mt_id" value="{{ $_GET['mt_id'] }}" required> 

              @foreach( $question_list as $qu_id => $question ) 
              
              <div class="form-group">
                <label><h4> {{ $question['prop']['QuestionName'] }} </h4></label>
                <div class="input-group input-xlarge margin-left-20">
                  
                  @if($question['prop']['QuestionTypeName'] == 'numeric') 
                    <span class="input-group-addon"></span>
                    <input type="number" class="form-control" placeholder="" name="answers[{{ $qu_id }}]" required> 
                  @endif
                  @if($question['prop']['QuestionTypeName'] == 'boolean')
                    <div class="mt-radio-inline">
                          <label class="mt-radio">
                              <input type="radio" name="answers[{{ $qu_id }}]" value="true"> Yes
                              <span></span>
                          </label>
                          <label class="mt-radio">
                              <input type="radio" name="answers[{{ $qu_id }}]" value="false" required> No
                              <span></span>
                          </label>
                      </div>
                  @endif
                  @if( $question['prop']['QuestionTypeName'] == 'multiple' ) 
                  <?php 
                    $options = array_unique( $question['prop']['QuestionValue'] );
                  ?>
                    <select  class="form-control" name="answers[{{ $qu_id }}]">
                      <option></option> 
                      <option> Not listed below / Not applicable </option> 
                      @foreach ( $options as $option )
                        <option value="{{ $option }}"> {{ $option }} </option>
                      @endforeach
                    </select>
                  @endif
                </div>
              </div>

              @endforeach
            </div>
    
        </div>
      </div>
      <!-- Navigation -->
      <div class="row">
          <br>
          <div class="col-xs-4 col-lg-3 pull-left">
            <a href="#" class="btn grey-mint btn-block btn-lg pull-left" id="back"><span><i class="fa fa-lg fa-angle-left"></i>&nbsp; Back</span></a>
          </div>
          <div class="col-xs-4 col-lg-3 pull-right">
            <button type="submit" class="btn green-jungle btn-block btn-lg pull-right">View Matches</button>
          </div>
      </div>   
    </form>
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
@endsection

@section('scripts')
    <script src="/js/questions.js?id={{ str_random(6) }}"></script>
@endsection
