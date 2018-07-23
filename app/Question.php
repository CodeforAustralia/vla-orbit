<?php
namespace App;

/**
 * Question Model.
 * Model for the Question functionalities
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */
Class Question extends OrbitSoap
{
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