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
    <div class="col-xs-3 mt-step-col active">
      <div class="mt-step-number bg-white">
        <i class="fa fa-legal"></i>
      </div>
      <div class="mt-step-title font-grey-cascade">Legal Issue</div>
      <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-3 mt-step-col">
      <div class="mt-step-number bg-white">
        <i class="fa fa-check-square-o font-grey-cascade"></i>
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
 
<!-- Legal Issue -->
<div class="row"> 
  <div class="col-xs-10 col-lg-8 col-xs-offset-1 col-lg-offset-2">
    <div class="portlet light">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject font-green sbold"><i class="icon-list font-green"></i>&nbsp;  Legal Issue</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row">
          <div class="col-xs-12">
            <p>To help you match the client to an appropriate service Orbit needs to know the specific legal issue that best matches the client's matter. Only one specific issue can be selected at a time.</p>
          </div>
          <div class="col-xs-12">
            <div class="form-group">
              <label for="single" class="control-label">Select or search for a Legal Issue:</label>
              <div class="input-group select2-bootstrap">
                <select id="single" class="form-control select2">
                    <option> </option>
                    @foreach( $matters as $matter )
                    <optgroup label="{{ $matter['MatterName'] }}">

                      @if( isset( $matter['children'] ) )

                          @foreach( $matter['children'] as $first_child )

                              @if( isset( $first_child['children'] ) )

                                  @foreach( $first_child['children'] as $second_child )

                                      @if( isset( $second_child['children'] ) )
                                          
                                          @foreach( $second_child['children'] as $third_child )

                                              <option value="{{ $third_child['MatterID'] }}">
                                                {{ $second_child['MatterName'] }} > {{ $third_child['MatterName'] }}
                                              </option>

                                          @endforeach
                                        
                                      @else
                                          <option value="{{ $second_child['MatterID'] }}">
                                            {{ $first_child['MatterName'] }} > {{ $second_child['MatterName'] }}
                                          </option>
                                      @endif

                                  @endforeach

                              @else
                                  <option value="{{ $first_child['MatterID'] }}">
                                    {{ $matter['MatterName'] }} > {{ $first_child['MatterName'] }}
                                  </option>

                              @endif

                          @endforeach

                      @endif

                    </optgroup>
                    @endforeach
                </select>
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
    <div class="col-xs-3 pull-left">
      <a href="./location" class="btn grey-mint btn-block btn-lg pull-left"><span><i class="fa fa-lg fa-angle-left"></i>&nbsp; Back</span></a>
    </div>
    <div class="col-xs-3 pull-right">
      <a href="#" id="next-legal_issue" class="btn green-jungle btn-block btn-lg pull-right"><span>Next &nbsp;<i class="fa fa-lg fa-angle-right"></i></span></a>
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
<div class="row">
    <div class="col-xs-12">
        <br>
        <br>
    </div>
</div>
@endsection

@section('scripts')
    <script src="/js/legal_issue.js?id={{ str_random(6) }}"></script>
@endsection

@section('inline-scripts')
  $(document).ready(function() {
    $('#single').select2({placeholder: "Select an issue"});
  });
@endsection

