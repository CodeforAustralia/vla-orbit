
    <div class="form-group">
        <label class="col-md-3 control-label">Choose Questions</label>
        <div class="col-md-4">
            <a class=" btn dark btn-outline sbold" data-toggle="modal" href="#long"> Choose </a>
        </div>
    </div>

    <div id="long" class="modal fade modal-scroll" tabindex="-1" data-replace="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Choose questions related to this matter and set a default value</h4>
                </div>
                <div class="modal-body">
                    @foreach($questions as $question)

                    <?php 
                    $exist_current_op_val = isset( $current_matter ) && 
                                            is_array($current_matter->MatterQuestions) &&  
                                            isset( $current_matter->MatterQuestions[ $question['QuestionId'] ] );

                    $checked = '';
                    if($exist_current_op_val)
                    {
                        $current_op_val =   [
                                                'operator' => $current_matter->MatterQuestions[ $question['QuestionId'] ]['operator'], 
                                                'value' => $current_matter->MatterQuestions[ $question['QuestionId'] ]['value'] 
                                            ];
                        $checked = 'checked';
                    } else {
                        $current_op_val = ['operator' => '', 'value' => ''];
                    }            
                    ?>

                    <div class="form-group">
                        <div class="col-md-2">
                            <input type="checkbox" name="question[{{ $question['QuestionId'] }}][check]" {{ $checked }}>
                        </div>
                        <label class="col-md-5 control-label">
                        {{ ( $question['QuestionLabel'] != '' ? $question['QuestionLabel'] : $question['QuestionName'] ) }}
                        </label>
                        <div class="col-md-2">
                            <select  class="form-control" name="question[{{ $question['QuestionId'] }}][operator]" id="operator">
                                
                                <option></option> 
                                <option value=">"  {{ ( $current_op_val['operator'] == '>'  ) ? 'selected' : '' }} > >  </option>
                                <option value=">=" {{ ( $current_op_val['operator'] == '>=' ) ? 'selected' : '' }} > >= </option>
                                <option value="<"  {{ ( $current_op_val['operator'] == '<'  ) ? 'selected' : '' }} > <  </option>
                                <option value="<=" {{ ( $current_op_val['operator'] == '<=' ) ? 'selected' : '' }} > <= </option>
                                <option value="="  {{ ( $current_op_val['operator'] == '='  ) ? 'selected' : '' }} > =  </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control"  name="question[{{ $question['QuestionId'] }}][answer]" id="answer"  value="{{ $current_op_val['value'] }}">
                        </div>
                    </div>

                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script src="#"></script>
@endsection

@section('inline-scripts')
         
@endsection