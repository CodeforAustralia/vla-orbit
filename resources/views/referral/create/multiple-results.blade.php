<?php
  $filter_type  = array_unique( array_column( $matches, 'ServiceTypeName'  ) );
  $filter_level = array_unique( array_column( $matches, 'ServiceLevelName' ) );
  $filter_sp_type = array_unique( array_column( $matches, 'ServiceProviderTypeName' ) );
  $is_admin = \App\Http\helpers::getRole() === 'Administrator';
?>
<div class="page-bar">
    <div class="page-toolbar">
        
        <div class="filter">

            <button type="button" class="btn bg-green bg-font-green sendNRE" data-toggle="modal" data-target="#sendNRE">Send Info Email</button>  

            <select class="mt-multiselect btn btn-default" id="filter-group" multiple="multiple">  
                <optgroup label="Service Provider" class="filter_sp_type">              
                    @foreach( $filter_sp_type as $filter )
                        @if( $filter!= '' )
                            <option value="{{ str_replace( ' ', '-', strtolower( $filter ) ) }}"> {{ $filter }} </option>
                        @endif
                    @endforeach
                </optgroup>
                <optgroup label="Service Type" class="filter_type">              
                    @foreach( $filter_type as $filter )
                        @if( $filter!= '' )
                            <option value="{{ str_replace( ' ', '-', strtolower( $filter ) ) }}"> {{ $filter }} </option>
                        @endif
                    @endforeach
                </optgroup>
                <optgroup label="Service Level" class="filter_level">              
                    @foreach( $filter_level as $filter )
                        @if( $filter!= '' )
                            <option value="{{ str_replace( ' ', '-', strtolower( $filter ) ) }}"> {{ $filter }} </option>
                        @endif
                    @endforeach
                </optgroup>
            </select>
        </div>

    </div>
</div>

@foreach( $matches as $match )

    <?php    
    
      $service_actions = array_column( $match['ServiceActions'], 'Action' );
      $current_sp_pos  = array_search( $match['ServiceProviderId'],  array_column( $service_providers, 'ServiceProviderId' ) );
      $current_sp_logo = $service_providers[ $current_sp_pos ]['ServiceProviderLogo'];
      $filters_class   = str_replace( ' ', '-', strtolower( $match['ServiceLevelName'] ) ) . ' ' . str_replace( ' ', '-', strtolower( $match['ServiceTypeName'] ) ) . ' ' . str_replace( ' ', '-', strtolower( $match['ServiceProviderTypeName'] ) ) ;
      $allows_book_and_refer = empty( array_diff( ['BOOK', 'REFER'], $service_actions) );
    ?>

    <div class="card-container col-xs-12 col-sm-6 col-xl-4 {{ $filters_class }}" id="{{ $match['ServiceId'] }}">
        <div class="col-xs-12 form-group result-card padding-0">
            <div class="row card-top-info">

                <div class="col-xs-12 col-sm-5">

                    <div class="col-sm-12"> 
                        <img src="{{ $current_sp_logo }}">
                    </div>              
                    @if( $match['URL'] != '#')
                    <div class="col-sm-12 website"> 
                        <a href="http://{{ $match['URL'] }}" target="_blank">Visit website</a>
                    </div>
                    @endif
                    
                </div>

                <div class="col-xs-12 col-sm-7 padding-0">
                    <h3 class="margin-0 service-name"><strong>{{ $match['ServiceName'] }}</strong></h3>
                    <h4 class="service-provider-name">{{ $match['ServiceProviderName'] }}</h4>
                    @if( $match['Location'] != '#')                        
                    <p>
                        <i class="fa fa-map-marker" aria-hidden="true"></i> 
                        <a href="http://maps.google.com/?q={{ $match['Location'] }}" target="_blank">{{ mb_strimwidth($match['Location'], 0, 40, "...") }}</a>
                    </p>                        
                    @endif                        
                    @if( $match['Phone'] != '#') 
                    <p><i class="fa fa-phone" aria-hidden="true"></i> {{ $match['Phone'] }}</p>
                    @endif
                    @if( $match['OpenningHrs'] != '#' && $match['OpenningHrs'] != 'TBA' )
                    <p> <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $match['OpenningHrs'] }} </p>
                    @endif
                    @if( $match['Wait'] != '#' && $match['Wait'] != 'TBA' )
                    <p> <i class="fa fa-hourglass-end" aria-hidden="true"></i> {{ $match['Wait'] }} </p>
                    @endif
                </div>
                
            </div>
            <hr>
            <div class="row card-body-info">

                <div class="col-xs-6">
                    <span> 
                        <strong>Service Level:</strong></span> <span id="service-level">{{ $match['ServiceLevelName'] }} 
                    </span>
                </div>

                <div class="col-xs-6">
                    <span> 
                        <strong>Service Type:</strong></span> <span id="service-type">{{ $match['ServiceTypeName'] }}
                    </span>
                </div>

                <div class="col-xs-12 description">
                    {!! $match['Description'] !!}
                    <p>&nbsp;</p>
                </div>
                
            </div>

            <div class="row bottom-buttons margin-0">

                @if( (in_array('BOOK', $service_actions) || $is_admin ) && $match['BookingServiceId'] != '' )

                <div class="col-xs-{{ ( $allows_book_and_refer || $is_admin ? '6' : '12' )  }} padding-0 book-button bg-blue">    

                    <button type="button" class="btn bg-blue bg-font-blue open-booking" data-toggle="modal" data-target="#SelectBooking" id="{{ $match['BookingServiceId']}}-{{ $match['BookingInterpritterServiceId']}}-{{ $match['ServiceProviderId']}}">Make booking</button>                 

                </div>

                @endif

                @if( in_array('REFER', $service_actions) || $is_admin )

                <div class="col-xs-{{ ( ($allows_book_and_refer || $is_admin) && $match['BookingServiceId'] != '' ? '6' : '12' )  }} padding-0 refer-button bg-green-jungle"> 

                    <button type="button" class="btn bg-green-jungle bg-font-green-jungle open-modal" data-toggle="modal" data-target="#SelectMatch">Send to client</button>

                </div>

                @endif
                
                
            </div>
        </div>
    </div>

  @endforeach
