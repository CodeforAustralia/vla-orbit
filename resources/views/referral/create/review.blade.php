@extends ('orbit.master')

@section ('content')
<!-- Review -->
<div class="row">
  <div class="col-xs-8 col-xs-offset-2">
    <div class="portlet light">
      <div class="portlet-body form">
        <form class="form-horizontal" role="form">
          <div class="form-body">
            <div class="row">
              <div class="col-xs-12">
                <h1 class="margin-bottom-20">Review client information</h1>
                <h3 class="margin-bottom-20"></h3>
              </div>
            </div>
            <!-- Location -->
            <div class="row">
              <div class="col-xs-12">
                <h3 class="form-section">Location &nbsp;<i class="fa fa-pencil" style="cursor: pointer;" onclick="window.location='./location';"></i></h3>
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-2">Suburb:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                      <p class="form-control-static">Melbourne</p>
                    </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-2">Postcode:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                      <p class="form-control-static">3000</p>
                    </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-2">Council:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                      <p class="form-control-static">City of Melbourne</p>
                    </div>
                </div>
              </div> 
            </div>  
            <!-- Legal Issue -->
            <div class="row">
              <div class="col-xs-12">
                <h3 class="form-section">Legal Issue &nbsp;<i class="fa fa-pencil" style="cursor: pointer;" onclick="window.location='./legal_issue';"></i></h3>
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-2">Legal Area:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                      <p class="form-control-static">Criminal</p>
                    </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-2">Legal Group:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                      <p class="form-control-static">Offences</p>
                    </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-2">Specific Issue:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                      <p class="form-control-static">Assault</p>
                    </div>
                </div>
              </div> 
            </div>
            <!-- Client Details -->
            <div class="row">
              <div class="col-xs-12">
                <h3 class="form-section">Client Details &nbsp;<i class="fa fa-pencil" style="cursor: pointer;" onclick="window.location='./details';"></i></h3>
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-2">Category 1:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                      <p class="form-control-static">Tag 1</p>
                    </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-2">Category 2:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                      <p class="form-control-static">Tag 1, Tag 2, Tag 3</p>
                    </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-2">Category 3:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                      <p class="form-control-static">None</p>
                    </div>
                </div>
              </div> 
            </div>
            <!-- Matching Questions -->
            <div class="row">
              <div class="col-xs-12">
                <h3 class="form-section">Matching Questions   &nbsp;<i class="fa fa-pencil" style="cursor: pointer;" onclick="window.location='./questions';"></i></h3>
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-5">Question 1: What is the question and is it very long or not?</label>
                    <div class="col-xs-12 col-sm-6">
                      <p class="form-control-static">Yes</p>
                    </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-5">Question 2: What is the question and is it very long or not?</label>
                    <div class="col-xs-12 col-sm-6">
                      <p class="form-control-static">$400</p>
                    </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-5">Question 3: What is the question and is it very long or not?</label>
                    <div class="col-xs-12 col-sm-6">
                      <p class="form-control-static">12/02/2017</p>
                    </div>
                </div>
              </div> 
            </div>
              </div>
          </div>
        </form>
        <!-- Button -->
        <div class="row">
          <div class="col-xs-10 col-xs-offset-1"><br>
            <div class="col-xs-6 col-xs-offset-3 margin-bottom-20">
              <a href="../result" class="btn green-jungle btn-block btn-lg pull-right"><span>View Results</span></a><br>
            </div>
          </div>
        </div>  
        
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
</div>

@endsection