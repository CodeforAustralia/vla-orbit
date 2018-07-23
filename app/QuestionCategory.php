<?php
namespace App;

/**
 * Question Category Model.
 * Model for the Question Category functionalities
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */

Class QuestionCategory extends OrbitSoap
{
    /**
     * Get All Question Categories
     *
     * @return array Array with All Question Categories
     */
    public function getAllQuestionCategories()
    {
        $question_categories = json_decode(
                                                $this
                                                ->client
                                                ->ws_init('GetAllQuestionCategoriesasJSON')
                                                ->GetAllQuestionCategoriesasJSON()
                                                ->GetAllQuestionCategoriesasJSONResult
                                                , true
                                            );

        return $question_categories;
    }

    /**
     * Save Question Category
     *
     * @param array $question_category Array with information of Question Category
     * @return array Array with error or success message
     */
    public function saveQuestionCategory( $question_category )
    {
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $question_category['CreatedBy'] = auth()->user()->name;
        $question_category['UpdatedBy'] = auth()->user()->name;
        $question_category['CreatedOn'] = $date_time;
        $question_category['UpdatedOn'] = $date_time;

        // Create call request
        $info = [ 'ObjectInstance' => $question_category ];

        try {
            $response = $this->client->ws_init('SaveQuestionCategoy')->SaveQuestionCategoy( $info );

            if ($response->SaveQuestionCategoyResult) {
                return ['success' => 'success' , 'message' => 'Question Category saved.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Delete Question Category by ID
     *
     * @param integer $qc_id Question Category ID
     * @return array Array with error or success message
     */
    public function deleteQuestionCategory( $qc_id )
    {
        // Create call request
        $info = [ 'RefNumber' => $qc_id ];

        try {
            $response = $this->client->ws_init('DeleteQuestionCategoy')->DeleteQuestionCategoy( $info );
            if ( $response->DeleteQuestionCategoyResult ) {
                return ['success' => 'success' , 'message' => 'Question Category deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }
}