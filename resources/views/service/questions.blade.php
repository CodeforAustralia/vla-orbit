
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
                    @foreach($current_service->ServiceMatters as $cs_Legal_matter)

                    <h5><strong>{{ $cs_Legal_matter->MatterName }}</strong></h5>
                        @foreach( $cs_Legal_matter->MatterQuestions as $cs_Legal_matter )

                            <div class="form-group">
                                <div class="col-md-1">
                                </div>
                                <label class="col-md-4">{{ $cs_Legal_matter->QuestionName }}</label>
                                <div class="col-md-2">
                                    <select  class="form-control" name="question[{{ $cs_Legal_matter->QuestionId }}][operator]" id="operator">
                                        <?php // If no prevous value saved - Show default value
                                            $current_op_val = ['operator' => $cs_Legal_matter->Operator, 'value' => $cs_Legal_matter->QuestionValue];
                                            //TO-DO get previous answer
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
                                    <input type="text" class="form-control"  name="question[{{ $cs_Legal_matter->QuestionId }}][answer]" id="answer"  value="{{ $current_op_val['value'] }}">
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>                        
                        @endforeach

                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                </div>
            </div>
        </div>
    </div>