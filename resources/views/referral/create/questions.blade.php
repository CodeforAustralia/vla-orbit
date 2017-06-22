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
    <div class="col-xs-3 mt-step-col done" style="cursor: pointer;" onclick="window.location='./legal_issue';">
        <div class="mt-step-number bg-white">
            <i class="fa fa-legal"></i>
        </div>
        <div class="mt-step-title font-grey-cascade">Legal Issue</div>
        <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-3 mt-step-col done" style="cursor: pointer;" onclick="window.location='./details';">
        <div class="mt-step-number bg-white">
            <i class="fa fa-check-square-o"></i>
        </div>
        <div class="mt-step-title font-grey-cascade">Client Details</div>
        <div class="mt-step-content font-grey-cascade"></div>
    </div>
    <div class="col-xs-3 mt-step-col last active">
        <div class="mt-step-number bg-white">
            <i class="fa fa-question"></i>
        </div>
        <div class="mt-step-title font-grey-cascade">Matching Questions</div>
        <div class="mt-step-content font-grey-cascade"></div>
    </div>
  </div>
</div>

<!-- Questions -->
<div class="row">
  <div class="col-xs-10 col-lg-8 col-xs-offset-1 col-lg-offset-2">
    <div class="portlet light">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject font-green sbold"><i class="icon-question font-green"></i>&nbsp; Matching Questions</span>
        </div>
      </div>
      <div class="portlet-body">
        <form role="form">
          <div class="form-body">
            <!-- Dropdown -->
            <div class="form-group"> 
              <label><h4><strong>Question 1:</strong> What is the client's employment status?</h4></label>
              <div class="input-group input-xlarge">
                <select class="form-control input-lg">
                  <option>Employed</option>
                  <option>Unemployed</option>
                  <option>Self Employed</option>
                  <option>Centrelink</option>
                </select>
              </div>
            </div>
            <!-- Num - Currency -->
            <div class="form-group">
              <label><h4><strong>Question 2:</strong> What is the client's weekly income after tax?</h4></label>
              <div class="input-group input-xlarge margin-left-20">
                <span class="input-group-addon"><i class="fa fa-dollar font-dark"></i></span>
                <input type="text" class="form-control input-lg" placeholder=""> 
              </div>
            </div>
            <!-- Number -->
            <div class="form-group">
              <label><h4><strong>Question 3:</strong> How many charges does the client have?</h4></label>
              <div class="input-group input-xlarge">
                <input type="number" class="form-control input-lg" placeholder=""> 
              </div>
            </div>            
            <!-- Radio - Yes/No -->  
            <div class="form-group">
              <label><h4><strong>Question 4:</strong> Does the client have a court summons for this charge?</h4></label>
              <div class="mt-radio-list col-xs-12">
                <label class="mt-radio mt-radio-outline"> Yes
                  <input type="radio" class="form-control input-lg" value="1" name="test">
                  <span></span>
                </label>
                <label class="mt-radio mt-radio-outline"> No
                  <input type="radio" class="form-control" value="1" name="test">
                  <span></span>
                </label>
              </div>
            </div>
            <!-- Date -->                
            <div class="form-group">
              <label><h4><strong>Question 5:</strong> When is the client's court date?</h4></label>
                <div class="input-group input-xlarge">
                    <input class="form-control input-lg" type="date" value="01/01/2017" id="example-date-input">
                </div>
            </div>
            <!-- Checkbox -->
            <div class="form-group">
              <label><h4><strong>Question 6:</strong> Do any of the following apply to the client?</h4></label>
              <div class="mt-checkbox-list">
                <label class="mt-checkbox mt-checkbox-outline"> Checkbox 1
                  <input type="checkbox" class="form-control" value="1" name="test">
                  <span></span>
                </label>
                <label class="mt-checkbox mt-checkbox-outline"> Checkbox 2
                  <input type="checkbox" class="form-control" value="1" name="test">
                  <span></span>
                </label>
                <label class="mt-checkbox mt-checkbox-outline"> Checkbox 3
                  <input type="checkbox" class="form-control"   value="1" name="test">
                  <span></span>
                </label>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Navigation -->
<div class="row">
  <div class="col-xs-10 col-lg-8 col-xs-offset-1 col-lg-offset-2"><br>
    <div class="col-xs-4 col-lg-3 pull-left">
      <a href="./details" class="btn grey-mint btn-block btn-lg pull-left"><span><i class="fa fa-lg fa-angle-left"></i>&nbsp; Back</span></a>
    </div>
    <div class="col-xs-4 col-lg-3 pull-right">
      <a href="./review" class="btn green-jungle btn-block btn-lg pull-right"><span>Next &nbsp;<i class="fa fa-lg fa-angle-right"></i></span></a>
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
    <!-- END PAGE HEADER-->
@endsection