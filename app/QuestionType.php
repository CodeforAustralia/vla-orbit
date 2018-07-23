<?php
namespace App;

/**
 * Question Group Model.
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */
Class QuestionType extends OrbitSoap
{
    /**
     * Get all Question Types
     *
     * @return array Array with Question Types
     */
    public function GetAllQuestionTypes()
    {
        $question_types = json_decode(
                                        $this
                                        ->client
                                        ->ws_init('GetAllQuestionTypessasJSON')
                                        ->GetAllQuestionTypessasJSON()
                                        ->GetAllQuestionTypessasJSONResult
                                        , true
                                    );

        return $question_types;
    }

    /**
     * Save Question Type
     *
     * @param array $question_type Array with information of Question Type
     * @return array Array with error or success message
     */
    public function saveQuestionType( $question_type )
    {
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $question_type['CreatedBy'] = auth()->user()->name;
        $question_type['UpdatedBy'] = auth()->user()->name;
        $question_type['CreatedOn'] = $date_time;
        $question_type['UpdatedOn'] = $date_time;

        $info = [ 'ObjectInstance' => $question_type ];

        try {
            $response = $this->client->ws_init('SaveQuestionType')->SaveQuestionType( $info );

            if ($response->SaveQuestionTypeResult) {
                return ['success' => 'success' , 'message' => 'Question Type saved.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Delete Question Type by ID
     *
     * @param integer $qt_id Question Type ID
     * @return array Array with error or success message
     */
    public function deleteQuestionType( $qt_id )
    {
        $info = [ 'RefNumber' => $qt_id ];

        try {
            $response = $this->client->ws_init('DeleteQuestionType')->DeleteQuestionType( $info );
            if ($response->DeleteQuestionTypeResult) {
                return ['success' => 'success' , 'message' => 'Question Type deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }
}