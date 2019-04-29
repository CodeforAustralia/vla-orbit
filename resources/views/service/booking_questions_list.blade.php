
    <!-- Questions container -->
    <div>
        @foreach( $service_booking_questions as $booking_question )
        <div class="form-group">
            <!-- Label container -->
            <div class="col-md-5">
                <label class="pull-right">
                {{ ( $booking_question['QuestionName'] != '' ? $booking_question['QuestionName'] : $booking_question['QuestionLabel'] ) }}
                </label>
            </div>
            <!-- end Label container -->
            <!-- Individual question container -->
            <div class="col-md-2">
                    <?php
                        // Get previous answer
                        $booking_answer = array_search(
                                                $booking_question['QuestionId'],  //Question id
                                                array_column(
                                                    $current_service->ServiceBookingQuestions,  // Array of previous Answers
                                                    'QuestionId' //Column to search an specific answer
                                                )
                                            );
                        $var1 = array_column(
                                                    $current_service->ServiceBookingQuestions,  // Array of previous Answers
                                                    'QuestionId' //Column to search an specific answer
                        );

                        //Initialize values
                        $current_op_val = [
                                            'operator' => '',
                                            'value' => ''
                                        ];

                        if($booking_answer !== false)
                        {
                            $current_answer = $current_service->ServiceBookingQuestions[ $booking_answer ];
                            $current_op_val = [
                                                'operator' => $current_answer->Operator,
                                                'value' => $current_answer->QuestionValue
                                            ];
                        }

                    ?>
                <select  class="form-control" name="booking_question[{{ $booking_question['QuestionId'] }}][operator]" id="operator">
                    <option></option>
                    <option value=">"  {{ ( $current_op_val['operator'] == '>'  ) ? 'selected' : '' }} > >  </option>
                    <option value=">=" {{ ( $current_op_val['operator'] == '>=' ) ? 'selected' : '' }} > >= </option>
                    <option value="<"  {{ ( $current_op_val['operator'] == '<'  ) ? 'selected' : '' }} > <  </option>
                    <option value="<=" {{ ( $current_op_val['operator'] == '<=' ) ? 'selected' : '' }} > <= </option>
                    <option value="="  {{ ( $current_op_val['operator'] == '='  ) ? 'selected' : '' }} > Equal  </option>
                    <option value="!=" {{ ( $current_op_val['operator'] == '!=' ) ? 'selected' : '' }} > Not equal </option>
                    <option value="in" {{ ( $current_op_val['operator'] == 'in' ) ? 'selected' : '' }} > IN  </option>
                </select>
            </div>
            <!-- end Individual question container -->
            <!-- Individual Answer container -->
            <div class="col-md-4">
                <input type="text" class="form-control"  name="booking_question[{{ $booking_question['QuestionId'] }}][answer]" id="answer"  value="{{ $current_op_val['value'] }}" {{ ($booking_question['QuestionTypeName'] == 'multiple' ? 'data-role=tagsinput': '') }}>
            </div>
            <!-- end Individual Answer container -->
        </div>
        @endforeach
    </div>
    <!-- end Questions container -->