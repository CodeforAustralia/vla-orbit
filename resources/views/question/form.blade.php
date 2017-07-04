
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>{{ $type_name }}</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/question" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">

                    <div class="form-group hidden">
                        <input type="text" class="form-control" id="qu_id" name="qu_id" value="{{ isset($current_question) ? $current_question->QuestionId : 0 }}" required>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Label:</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="QuestionLabel" name="QuestionLabel"  value="{{ isset($current_question) ? $current_question->QuestionLabel : '' }}" required>
                        </div>
                    </div>

	                <div class="form-group">
	                  	<label class="col-md-3 control-label">Question:</label>
	                  	<div class="col-md-4">
		                  	<input type="text" class="form-control" id="QuestionName" name="QuestionName"  value="{{ isset($current_question) ? $current_question->QuestionName : '' }}" required>
		                </div>
	                </div>
                    
                    <div class="form-group {{ ( sizeof($question_types) > 1 ? '' : 'hidden' ) }} ">
                        <label class="col-md-3 control-label">Type</label>
                        <div class="col-md-4">
                            <select class="form-control" id="QuestionTypeId" name="QuestionTypeId">
                            @foreach($question_types as $question_type)
                                <option value="{{ $question_type['QuestionTypeId'] }}"  {{ (isset($current_question) && $question_type['QuestionTypeId'] ==  $current_question->QuestionTypeId ) ? 'selected' : '' }} >{{ $question_type['QuestionTypeName'] }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group hidden">
                        <label class="col-md-3 control-label">Question Category</label>
                        <div class="col-md-4">
                            <select class="form-control" id="QuestionCategoryId" name="QuestionCategoryId">
                            @foreach($question_categories as $question_category)
                                <option value="{{ $question_category['QuestionId'] }}"  {{ (isset($current_question) && $question_category['QuestionId'] ==  $current_question->QuestionCategoryId ) ? 'selected' : '' }} >{{ $question_category['QuestionName'] }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green">Submit</button>
                            <button type="button" class="btn btn-circle grey-salsa btn-outline">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>