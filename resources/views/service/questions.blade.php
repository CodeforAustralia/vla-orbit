
    <div class="form-group">
        <label class="col-md-2 tooltips" data-container="body" data-placement="right" data-original-title="If you have updated your legal matters please save and then choose questions">Legal Matter Questions ({{ sizeof($current_service->ServiceMatters) }})</label>
        <div class="col-md-4">
            <a class=" btn dark btn-outline sbold tooltips" data-toggle="modal" href="#long"  data-container="body" data-placement="right" data-original-title="If you have updated your legal matters please save and then choose questions"> Choose </a>
        </div>
    </div>

    <div id="long" class="modal fade modal-scroll" tabindex="-1" data-replace="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Set Question values related to this service</h4>
                </div>
                <div class="modal-body">
                    
                    <ul class="nav nav-tabs">
                    @foreach( array_column( $current_service->ServiceMatters, 'MatterName' ) as $pos => $matter_name )                    
                        <li class="{{ ($pos == 0 ? 'active' : '') }}" ><a data-toggle="tab" href="#{{ str_replace(' ' , '-', $matter_name) }}">{{ $matter_name }}</a></li>
                    @endforeach
                    </ul>
                    
                    <div class="tab-content">
                    @foreach($current_service->ServiceMatters as $pos => $cs_Legal_matter)

                        <div id="{{ str_replace(' ' , '-', $cs_Legal_matter->MatterName) }}" class="tab-pane fade {{ ($pos == 0 ? 'in active' : '') }}">
                        @foreach( $cs_Legal_matter->MatterQuestions as $cs_Legal_matter_question )

                            <div class="form-group">
                                <div class="col-md-1">
                                </div>
                                <label class="col-md-4">{{ $cs_Legal_matter_question->QuestionName }}</label>
                                <div class="col-md-2">
                                    <select  class="form-control" name="question[{{ $cs_Legal_matter_question->QuestionId }}][operator]" id="operator">
                                        <?php 
                                            // If no prevous value saved - Show default value
                                            $current_op_val = [
                                                                'operator' => $cs_Legal_matter_question->Operator, 
                                                                'value' => $cs_Legal_matter_question->QuestionValue
                                                              ];
                                            // Get previous answer
                                            $lm_answer = array_search( 
                                                                    $cs_Legal_matter_question->QuestionId,  //Question id
                                                                    array_column(
                                                                        $cs_Legal_matter->MatterAnswers,  // Array of previous Answers
                                                                        'QuestionId' //Column to search an specific answer
                                                                    )
                                                                  );
                                            if($lm_answer !== false) 
                                            {
                                                $current_answer = $cs_Legal_matter->MatterAnswers[ $lm_answer ];
                                                $current_op_val = [ 
                                                                    'operator' => $current_answer->Operator, 
                                                                    'value' => $current_answer->QuestionValue 
                                                                  ];
                                            }
                                        ?>
                                        <option></option> 
                                        <option value=">"  {{ ( $current_op_val['operator'] == '>'  ) ? 'selected' : '' }} > >  </option>
                                        <option value=">=" {{ ( $current_op_val['operator'] == '>=' ) ? 'selected' : '' }} > >= </option>
                                        <option value="<"  {{ ( $current_op_val['operator'] == '<'  ) ? 'selected' : '' }} > <  </option>
                                        <option value="<=" {{ ( $current_op_val['operator'] == '<=' ) ? 'selected' : '' }} > <= </option>
                                        <option value="="  {{ ( $current_op_val['operator'] == '='  ) ? 'selected' : '' }} > =  </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control"  name="question[{{ $cs_Legal_matter_question->QuestionId }}][answer]" id="answer"  value="{{ $current_op_val['value'] }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control hidden"  name="question[{{ $cs_Legal_matter_question->QuestionId }}][mt_id]" id="answer"  value="{{ $cs_Legal_matter->MatterID }}">
                                </div>
                            </div>                        
                        @endforeach

                        <hr>
                        <h3>Eligibility Check</h3>

                        @foreach($vulnertability_questions as $vulnerability_question)                     
                            <label class="checkbox-inline">                                
                                <input type="checkbox" value="" name="vulnerability_matter[{{ $cs_Legal_matter->MatterID }}][{{ $vulnerability_question['QuestionId'] }}]" >
                                    {{ $vulnerability_question["QuestionName"] }}
                            </label>
                        @endforeach

                        </div>  
                    @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                </div>
            </div>
        </div>
    </div>