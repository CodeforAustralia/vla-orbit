@foreach( $matches as $match )
    <!-- Result -->
    <div class="portlet light">
      <div class="portlet-body">
        <div class="row service-card" id="{{ $match['ServiceId'] }}"> <!-- Card 1-->
          <!-- Card Container -->
          <div class="col-xs-12">
            <!-- Logo -->
            <div class="col-xs-4">
              <img src="https://pbs.twimg.com/profile_images/583201253980643328/NfKqUKrP.jpg" class="img-responsive img-thumbnail center-block">
            </div>
            <!-- Service Details -->
            <div class="col-xs-8">
              <div class="col-xs-12">
                <h3 class="margin-top-10 service-name"><strong>{{ $match['ServiceName'] }}</strong></h3>
                <h4 class="service-provider-name">{{ $match['ServiceProviderName'] }}</h4>
                <a href="http://maps.google.com/?q={{ $match['Location'] }}" target="_blank"><h5>{{ $match['Location'] }}</h5></a>
              </div>
              <!-- Description  -->
              <div class="col-xs-12 visible-xs-block visible-sm-block visible-md-block visible-lg-block visible-xl-block padding-bottom-20 padding-top-10">     
                <a class="btn-default bg-white dropdown-toggle" data-toggle="collapse" href="#description1" aria-expanded="false" aria"=false" aria-controls="description"  role="button"><strong>View Description <i class="fa fa-angle-down"></i></strong></a>      
                <div class="collapse col-xs-12" id="description1">              
                  <div class="col-xs-12"><br>
                    {{ $match['Description'] }}
                  </div>
                </div>
              </div>
              <!--Card Content Mid-->  
              <div class="col-xs-12 col-sm-12 col-md-4">   
                <p><strong>Open:</strong>  - </p>
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
              </div>    
            </div> <!-- Close Service Details-->
          </div> <!-- Close Card Col -->
        </div><!-- Close Card Row -->
      </div><!-- Close Portlet Body -->
    </div><!-- Result Portlet -->
  @endforeach