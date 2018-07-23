<?php
namespace App;

/**
 * Legal Matter Question Model.
 * Model for the legal matter question functionalities
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */

Class MatterQuestion extends OrbitSoap
{
    /**
     * Save Legal Matter Question
     *
     * @param array $matter_question Legal Matter Question information
     * @return array Array with error or success message
     */
    public function saveMatterQuestion( $matter_question )
    {
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $matter_question['CreatedBy'] = auth()->user()->name;
        $matter_question['UpdatedBy'] = auth()->user()->name;
        $matter_question['CreatedOn'] = $date_time;
        $matter_question['UpdatedOn'] = $date_time;

        $info = [ 'ObjectInstance' => $matter_question ];

        try {
            $response = $this->client->ws_init('SaveMatterQuestions')->SaveMatterQuestions( $info );

            if ( $response->SaveMatterQuestionsResult ) {
                return ['success' => 'success' , 'message' => 'Matter question saved.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Process Matter Questions deleting them from Legal Matter by ID and then saving last vaslues
     *
     * @param array $questions Array o
     * @param integer $mt_id Legal Matter ID
     * @return void
     */
    public function processMatterQuestions( $questions, $mt_id )
    {
        self::deleteMatterQuestionsByMatterID( $mt_id );

        foreach ( $questions as $qu_id => $qu_values ) {

            if ( isset( $qu_values['check'] ) ) {

                if ( is_null( $qu_values['operator'] ) || is_null( $qu_values['answer'] ) ) {
                    $qu_values['operator'] = '';
                    $qu_values['answer'] = '';
                }
                self::saveMatterQuestion( [
                                            'MatterId' => $mt_id,
                                            'QuestionId' => $qu_id,
                                            'Operator' => $qu_values['operator'],
                                            'QuestionValue' => $qu_values['answer']
                                           ]
                                        );
            }
        }
    }

    /**
     * Delete Matter Questions from Legal Matter by ID
     *
     * @param integer $mt_id Legal Matter ID
     * @return array Array with error or success message
     */
    public function deleteMatterQuestionsByMatterID( $mt_id )
    {
        $info = [ 'MatterId' => $mt_id ];

        try {
            $response = $this
                        ->client
                        ->ws_init('DeleteMatterQuestionsByMatterId')
                        ->DeleteMatterQuestionsByMatterId( $info );

            if ( $response->DeleteMatterQuestionsByMatterIdResult ) {
                return ['success' => 'success' , 'message' => 'Matter question deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }
}