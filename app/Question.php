<?php
namespace App;

/**
 * Question Model.
 * Model for the Question functionalities
 *
 * @author Christian Arevalo
 * @version 1.3.1
 * @see  OrbitSoap
 */
Class Question extends OrbitSoap
{
    /**
     * Get all questions
     *
     * @return Array    list of all questions
     */
    public function getAllQuestions()
    {
        $questions = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetAllQuestionsasJSON')
                                    ->GetAllQuestionsasJSON()
                                    ->GetAllQuestionsasJSONResult
                                    , true
                                );

        return $questions;
    }

    /**
     * Get all questions by category ID
     *
     * @param Int $qc_id   Question category ID
     * @return Array         Array Question Category information
     */
    public function getAllQuestionsByCategoryID( $qc_id )
    {
        $questions = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetAllQuestionsByCategoryIdasJSON')
                                    ->GetAllQuestionsByCategoryIdasJSON( [ 'CategoryId' => $qc_id ] )
                                    ->GetAllQuestionsByCategoryIdasJSONResult
                                    , true
                                );

        return $questions;
    }

    /**
     * Get all questions by ID
     *
     * @param Int $qu_id   Question ID
     * @return Array         Message of success with information or error
     */
    public function getAllQuestionById( $qu_id )
    {
        try {
            $question = json_decode(
                                        $this
                                        ->client
                                        ->ws_init('GetAllQuestionsByIdasJSON')
                                        ->GetAllQuestionsByIdasJSON( [ 'RefNumber' => $qu_id  ] )
                                        ->GetAllQuestionsByIdasJSONResult
                                    );
            return ['success' => 'success' , 'message' => 'Service.', 'data' => $question];
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }

    }

    /**
     * Save questions
     *
     * @param Array $question   Question information
     * @return Array            Message of success or error
     */
    public function saveQuestion( $question )
    {
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $question['CreatedBy'] = auth()->user()->name;
        $question['UpdatedBy'] = auth()->user()->name;
        $question['CreatedOn'] = $date_time;
        $question['UpdatedOn'] = $date_time;

        // Create call request
        $info = [ 'ObjectInstance' => $question ];

        try {
            $response = $this->client->ws_init('SaveQuestion')->SaveQuestion($info);

            if ($response->SaveQuestionResult) {
                return ['success' => 'success' , 'message' => 'Question saved.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Delete questions by ID
     *
     * @param Int $qu_id    Question ID
     * @return Array        Message of success or error
     */
    public function deleteQuestion( $qu_id )
    {
        // Create call request
        $info = [ 'RefNumber' => $qu_id ];

        try {
            $response = $this->client->ws_init('DeleteQuestion')->DeleteQuestion( $info );
            if ($response->DeleteQuestionResult) {
                return ['success' => 'success' , 'message' => 'Question deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Get all legal matter questions
     *
     * @return Array List of questions
     */
    public function getAllLegalMatterQuestions()
    {
        $questions = json_decode($this
                                ->client
                                ->ws_init('GetAllQuestionsByCategoryIdasJSON')
                                ->GetAllQuestionsByCategoryIdasJSON( [ 'CategoryId'  => 1 ] )
                                ->GetAllQuestionsByCategoryIdasJSONResult,
                                true);

        return $questions;
    }

    /**
     * Get vulnerability questions
     *
     * @return Array List of questions
     */
    public function getAllVulnerabilityQuestions()
    {
        $questions = json_decode($this
                                ->client
                                ->ws_init('GetAllQuestionsByCategoryIdasJSON')
                                ->GetAllQuestionsByCategoryIdasJSON( [ 'CategoryId'  => 2 ] )
                                ->GetAllQuestionsByCategoryIdasJSONResult, true);

        return $questions;
    }
}