<?php
namespace App;

/**
 * Service Booking Questions model for controlling questions to be asked when booking clients in services
 * @author Christian Arevalo
 * @version 1.0.1
 * @see  OrbitSoap
 */
Class ServiceBookingQuestions extends OrbitSoap
{
    /**
     * Get questions required to book a service during the intake process
     *
     * @return Array List of questions
     */
    public function getAllServiceBookingQuestions()
    {
        $questions = json_decode($this
                                ->client
                                ->ws_init('GetAllQuestionsByCategoryIdWithGroupasJSON')
                                ->GetAllQuestionsByCategoryIdWithGroupasJSON( [ 'CategoryId'  => 3 ] )
                                ->GetAllQuestionsByCategoryIdWithGroupasJSONResult, true);
        return $questions;
    }

    /**
     * Create or update service booking questions by service
     * @param  array $sbq_params service booking details
     * @return array            success or error message
     */
    public function saveServiceBookingQuestions( $sbq_params )
    {
        $info = [ 'ObjectInstance' => $sbq_params ];

        try {
            $response = $this
                        ->client
                        ->ws_init( 'SaveServiceBookingQuestions' )
                        ->SaveServiceBookingQuestions( $info );
            // Redirect to index
            if( $response->SaveServiceBookingQuestionsResult > 0 ) {
                return [ 'success' => 'success' , 'message' => 'Service Booking Questions saved.'];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }

    /**
     * Delete all service bookings by service ID
     * @param  int   $sv_id  service ID
     * @return array         success or error message
     */
    public function deleteServiceBookingQuestionsByServiceId($sv_id)
    {
        $info = [ 'ServiceId' => $sv_id ];
        try {
            $response = $this
                        ->client
                        ->ws_init('DeleteServiceBookingQuestionsByServiceId')
                        ->DeleteServiceBookingQuestionsByServiceId( $info );
            if ( $response->DeleteServiceBookingQuestionsByServiceIdResult ) {
                return [ 'success' => 'success' , 'message' => 'Service Booking Questions deleted.' ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }

    }
}