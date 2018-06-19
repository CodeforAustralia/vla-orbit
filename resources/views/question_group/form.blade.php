    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-question"></i>Question Group</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/question_group" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">

                    <div class="form-group hidden">
                        <input type="text" class="form-control" id="QuestionGroupId" name="QuestionGroupId" value="{{ isset($current_question_group) ? $current_question_group->QuestionGroupId : 0 }}" required>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Name</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Enter text" name="GroupName" id="GroupName" value="{{ isset($current_question_group) ? $current_question_group->GroupName : '' }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Question Group Parent</label>
                        <div class="col-md-4">
                            <select class="form-control" id="ParentId" name="ParentId">
                                <option value="0"></option>
                                @foreach($question_groups as $question_group)
                                <option value="{{ $question_group['QuestionGroupId'] }}"  {{ (isset($current_question_group) && $question_group['QuestionGroupId'] ==  $current_question_group->ParentId ) ? 'selected' : '' }} >{{ $question_group['GroupName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>