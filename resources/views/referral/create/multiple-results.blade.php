
<?php
	$filter_type  = array_unique( array_column( $matches, 'ServiceTypeName'  ) );
	$filter_level = array_unique( array_column( $matches, 'ServiceLevelName' ) );
?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <span>Filter By:</span>
        </li>
    </ul>
    <div class="page-toolbar">
        
        <div class="btn-group ">
        	<button class="btn blue dropdown-toggle btn-fit-height" data-toggle="dropdown">Service Type
	                <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu filter-type">
                <li >
                    <a href="javascript:;" class="all-type"> All                        
                    </a>
                </li>
            	@foreach( $filter_type as $filter )
                <li >
                    <a href="javascript:;" class="{{ str_replace( ' ', '-', strtolower( $filter ) ) }}"> {{ $filter }}                        
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="btn-group">
            <button class="btn blue dropdown-toggle btn-fit-height" data-toggle="dropdown">Service Level
                <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu filter-level">
                <li >
                    <a href="javascript:;" class="all-level"> All                        
                    </a>
                </li>
            	@foreach( $filter_level as $filter )
                <li >
                    <a href="javascript:;" class="{{ str_replace( ' ', '-', strtolower( $filter ) ) }}"> {{ $filter }}                        
                    </a>
                </li>
                @endforeach
            </ul>		            
        </div>
    </div>
</div>

@foreach( $matches as $match )

    <?php
      $current_sp_pos  = array_search( $match['ServiceProviderId'],  array_column( $service_providers, 'ServiceProviderId' ) );
      $current_sp_logo = $service_providers[ $current_sp_pos ]['ServiceProviderLogo'];
      $filters_class   = str_replace( ' ', '-', strtolower( $match['ServiceLevelName'] ) ) . ' ' . str_replace( ' ', '-', strtolower( $match['ServiceTypeName'] ) ) ;
    ?>
    <!-- Result -->
    <div class="portlet light {{ $filters_class }}">
      <div class="portlet-body">
        <div class="row service-card" id="{{ $match['ServiceId'] }}"> <!-- Card 1-->
          <!-- Card Container -->
          <div class="col-xs-12">
            <!-- Logo -->
            <div class="col-xs-12 col-sm-3">
              <img src="{{ $current_sp_logo }}" class="img-responsive img-thumbnail center-block">
            </div>
            <!-- Service Details -->
            <div class="col-xs-12 col-sm-9">
              <div class="col-xs-12">
                <h3 class="margin-top-10 service-name"><strong>{{ $match['ServiceName'] }}</strong></h3>
                <h4 class="service-provider-name">{{ $match['ServiceProviderName'] }}</h4>
                @if( $match['Location'] != '#')
                <a href="http://maps.google.com/?q={{ $match['Location'] }}" target="_blank"><h5>{{ $match['Location'] }}</h5></a>
                @endif
              </div>
              <!-- Description  -->
              <div class="col-xs-12 visible-xs-block visible-sm-block visible-md-block visible-lg-block visible-xl-block padding-bottom-20 padding-top-10">     
                <a class="btn-default bg-white dropdown-toggle" data-toggle="collapse" href="#description{{ $match['ServiceId'] }}" aria-expanded="false" aria"=false" aria-controls="description"  role="button"><strong>Service Details  <i class="fa fa-angle-down"></i></strong></a>      
                <div class="collapse col-xs-12" id="description{{ $match['ServiceId'] }}">              
                  <div class="col-xs-12"><br>
                    {!! $match['Description'] !!}
                  </div>
                </div>
              </div>
              <!--Card Content Mid-->  
              <div class="col-xs-12 col-sm-12 col-md-4">   
                @if( $match['URL'] != '#')
                <p><strong><a href="http://{{ $match['URL'] }}" target="_blank">Website</a></strong></p>
                @endif
                <p><strong>Wait Time:</strong> {{ $match['Wait'] }}</p>
              </div>
              <!--Card Content LHS-->
              <div class="col-xs-12 col-sm-12 col-md-4">   
                <p><strong>Service Type:</strong> {{ $match['ServiceTypeName'] }}</p>
                <p><strong>Service Level:</strong> {{ $match['ServiceLevelName'] }}</p>
              </div>
              <!--Card Content RHS-->  
              <div class="col-xs-12 col-sm-12 col-md-4"> 
                <!-- Trigger Modal -->
                <button type="button" class="btn green-jungle btn-block btn-lg pull-right open-modal" data-toggle="modal" data-target="#SelectMatch">Select Match</button>
                @if ( in_array( Auth::user()->roles()->first()->name, [ 'Administrator', 'VLA', 'AdminSp' ] ) && $match['BookingServiceId'] != '' )
                <button type="button" class="btn green-jungle btn-block btn-lg pull-right open-booking" data-toggle="modal" data-target="#SelectBooking" id="{{ $match['BookingServiceId']}}-{{ $match['BookingInterpritterServiceId']}}-{{ $match['ServiceProviderId']}}">Make Booking</button>
                @endif
              </div>    
            </div> <!-- Close Service Details-->
          </div> <!-- Close Card Col -->
        </div><!-- Close Card Row -->
      </div><!-- Close Portlet Body -->
    </div><!-- Result Portlet -->
  @endforeach
