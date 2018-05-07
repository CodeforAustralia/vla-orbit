@extends ('orbit.master')

@section ('content')

  <!-- BEGIN PAGE HEADER-->
  <div class="portlet ">
      <h1 class="page-title col-xs-10"> All Panel Lawyers </h1>      
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
                          <th width="5%"> Id </th>
                          <th width="25%"> Firm Name </th>
                          <th width="30%"> Address </th>
                          <th width="10%"> Phone </th>
                          <th width="20%"> Law Type </th> 
                      </tr>
                  </thead>
                  <tbody> </tbody>
              </table>
          </div>
      </div>
  </div>  

@endsection

@section('scripts')    
    <script src="/js/panel-lawyers.js?id=1" type="text/javascript"></script>
@endsection