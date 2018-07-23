<?php
namespace App;

use App\Service;

/**
 * Legal Matter Answer to Questions inside Services.
 * Model for the Matter Service Answer
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */

Class MatterServiceAnswer extends OrbitSoap
{
    /**
     * Save Legal Matter Answer in Service
     *
     * @param array $matter_question Legal Matter Answer in Service information
     * @return array Array with error or success message
     */
    public function saveMatterServiceAnswer( $matter_question )
    {
        $info = [ 'ObjectInstance' => $matter_question ];

        try {
            $response = $this->client->ws_init('SaveServiceMatterAnswers')->SaveServiceMatterAnswers( $info );

            if ( $response->SaveServiceMatterAnswersResult ) {
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
     * Process Answers to questions inside Services deleting them and adding the new Answers by Service ID
     *
     * @param array $questions Question and Answers information
     * @param integer $sv_id Service ID
     * @return void
     */
    public function processMatterServiceAnswer( $questions, $sv_id )
    {
        self::deleteMatterAnswerByServiceID( $sv_id );

        $service = new Service();
        $result  = $service->getServiceByID( $sv_id ); //Get the last version of that service

        if ( isset($result['data']) ) {
            $current_service = json_decode( $result['data'] )[0];

            foreach ( $questions as $question ) {

                foreach ( $question as $qu_id => $qu_values ) {

                    if ( is_null( $qu_values['operator'] ) || is_null( $qu_values['answer'] ) ) {
                        $qu_values['operator'] = ' ';
                        $qu_values['answer'] = ' ';
                    }
                    //Get service matter position of question in current service matter - Potentially slow for huge arrays
                    $sm_position = array_search(
                                                    $qu_values['mt_id'],  //Legal matter id
                                                    array_column(
                                                        $current_service->ServiceMatters,  // Array of matters in a service
                                                        'MatterID' //Column to search an specific matter
                                                    )
                                               );

                    if ( isset($current_service->ServiceMatters[$sm_position]) ) {

                        $lms_id = $current_service->ServiceMatters[$sm_position]->MatterServiceID;

                        self::saveMatterServiceAnswer(
                                                        [
                                                            'RefNo'           => 0,
                                                            'MatterServiceId' => $lms_id,
                                                            'QuestionId'      => $qu_id,
                                                            'Operator'        => $qu_values['operator'],
                                                            'QuestionValue'   => $qu_values['answer']
                                                        ]
                                                    );
                    }

                }
            }

        }

    }

    /**
     * Process Vulnerability Answers to questions inside Services deleting them and adding the new Answers by Service ID
     *
     * @param array $questions Question and Answers information
     * @param integer $sv_id Service ID
     * @return void
     */
    public function processVulnerabilityMatterServiceAnswer( $questions, $sv_id )
    {
        $service = new Service();
        $result  = $service->getServiceByID( $sv_id ); //Get the last version of that service

        if (isset($result['data']) && !is_null( $questions )) {

            $current_service = json_decode( $result['data'] )[0];
            foreach ( $questions as $lm_id => $qu_ids ) {

                $operator = '=';
                $answer = true;

                //Get service matter position of question in current service matter - Potentially slow for huge arrays
                $sm_position = array_search(
                                                $lm_id,  //Legal matter id
                                                array_column(
                                                    $current_service->ServiceMatters,  // Array of matters in a service
                                                    'MatterID' //Column to search an specific matter
                                                )
                                            );

                $lms_id = $current_service->ServiceMatters[$sm_position]->MatterServiceID;
                foreach ($qu_ids as $qu_id => $value) {

                    self::saveMatterServiceAnswer(
                                                    [
                                                        'RefNo'           => 0,
                                                        'MatterServiceId' => $lms_id,
                                                        'QuestionId'      => $qu_id,
                                                        'Operator'        => $operator,
                                                        'QuestionValue'   => $answer
                                                    ]
                                                );
                }

            }

        }

    }

    /**
     * Delete All legal Matter Answers by Service ID
     *
     * @param integer $sv_id Service ID
     * @return array Array with error or success message
     */
    public function deleteMatterAnswerByServiceID( $sv_id )
    {
        $info = [ 'ServiceId' => $sv_id ];

        try {
            $response = $this
                        ->client
                        ->ws_init('DeleteMatterAnswersByServiceId')
                        ->DeleteMatterAnswersByServiceId( $info );

            if ( $response->DeleteMatterAnswersByServiceIdResult ) {
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