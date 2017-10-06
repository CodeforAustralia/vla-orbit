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
              <p>Find a service by selecting the legal issue that best matches the client’s situation.</p>
              <p>Service providers may limit entry to services based on where a client lives, works or studies. Add a location to specify the search.</p>
          </div>
          <div class="col-xs-12">
            <div class="form-group">

                <div class="input-group select2-bootstrap col-xs-6 legal_issue">
                  <select id="single" class="form-control select2">
                      <option> </option>
                      @foreach( $matters as $matter )
                      <optgroup label="{{ $matter['MatterName'] }}">

                        @if( isset( $matter['children'] ) )

                            @foreach( $matter['children'] as $first_child )

                                @if( isset( $first_child['children'] ) )

                                    <option value="{{ $first_child['MatterID'] }}" disabled="disabled" class="specific_problem">
                                     {{ $first_child['MatterName'] }}
                                    </option>

                                    @foreach( $first_child['children'] as $second_child )

                                        @if( !isset( $second_child['children'] ) )
                                            <option value="{{ $second_child['MatterID'] }}">
                                              {{ $second_child['MatterName'] }}
                                            </option>
                                        @endif

                                    @endforeach

                                @else
                                    <option value="{{ $first_child['MatterID'] }}">
                                      {{ $first_child['MatterName'] }}
                                    </option>

                                @endif

                            @endforeach

                        @endif

                      </optgroup>
                      @endforeach
                  </select>
                </div>

                <div class="input-group select2-bootstrap-prepend col-xs-6 location">
                    
                    <select class="form-control" id="single-prepend-text" name="lga[]">   
                         <!-- option value="0" style="color: #999;">Search for suburbs, postcodes or councils</option --> 
                        <option> </option>
                    </select>                      
                </div>

            </div>
            <br>
            <br>
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