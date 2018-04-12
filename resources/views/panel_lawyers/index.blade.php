@extends ('orbit.master')

@section ('content')
  <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 400px;
        width: 30%;
      }
  </style>

  <!-- BEGIN PAGE HEADER-->
  <div class="portlet ">
      <h1 class="page-title col-xs-10"> All Panel Lawyers </h1>
      <a role="button" class="btn main-green col-xs-2 pull-right" href="/panel_lawyers/new">New Panel Laywer</a>
      <br>
  </div>
  <div class="portlet light portlet-fit portlet-datatable ">
      <div class="portlet-body">
          <div class="table-container">
              <div class="table-actions-wrapper">
                  <div id="service_filter" class="dataTables_filter"><label>Search: <input type="search" id="search_box" class="" placeholder="" aria-controls="service"></label></div>
              </div>
              <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_panel_lawyers">
                  <thead>
                      <tr role="row" class="heading">
                          <!-- <th width="10%"> Id </th> -->
                          <th width="10%"> Id </th>
                          <th width="20%"> Firm Name </th>
                          <th width="30%"> Address </th>
                          <th width="20%"> Phone </th>
                          <th width="10%"> </th> 
                      </tr>
                  </thead>
                  <tbody> </tbody>
              </table>
          </div>
      </div>
  </div>  

@endsection

@section('scripts')    
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJi9SNu8Nye5MDdZcB5DfcgsZjXgJk6cc">
    </script>  
    <script src="/js/panel-lawyers.js?id=1" type="text/javascript"></script>
@endsection