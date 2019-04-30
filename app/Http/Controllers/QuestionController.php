<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\QuestionGroup;
use App\QuestionType;
use App\QuestionCategory;
use App\ServiceBookingQuestions;
use Auth;

/**
 * Question  Controller.
 * Controller for the question functionalities
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  Controller
 */

const LEGAL_MATTER_TYPE = 1;
const LEGAL_MATTER_TYPE_URL = 'legal_matter_questions';
const ELEGIBILITY_TYPE = 2;
const ELEGIBILITY_TYPE_URL = 'eligibility_criteria';
const BOOKING_TYPE = 3;
const BOOKING_TYPE_URL = 'service_booking_questions';

class QuestionController extends Controller
{
    /**
     * Question contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of question
     * @return view question information
     */
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("question.index");
    }

    /**
     * Display a listing of legal matter questions
     * @return view legal matter question information
     */
    public function legalMatterQuestions()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("question.legal_matter_questions");
    }

    /**
     * Display a listing of eligibility questions
     * @return view eligibility question information
     */
    public function eligibilityCriteria()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("question.eligibility_criteria");
    }

    /**
     * Display a listing of Service booking questions
     * @return view eligibility question information
     */
    public function serviceBookingQuestions()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("question.service_booking_questions");
    }

    /**
     * Display a specific question
     * @param  int  $qu_id    question Id
     * @return view single question information page
     */
    public function show( $qu_id )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_type_obj = new QuestionType();
        $question_types = $question_type_obj->getAllQuestionTypes();

        $question_category_obj = new QuestionCategory();
        $question_categories = $question_category_obj->getAllQuestionCategories();

        $question = new Question();
        $current_question = $question->getAllQuestionById( $qu_id );

        $question_group = new QuestionGroup();
        $question_groups = $question_group->GetAllQuestionGroups();

        if (isset($current_question['data'])) {
            $current_question = $current_question['data'][0];
            $type_name = '';
            if ( $current_question->QuestionCategoryId == ELEGIBILITY_TYPE ) {
                $question_types =  [
                                        $question_types[
                                                            array_search(
                                                                    LEGAL_MATTER_TYPE ,
                                                                    array_column( $question_types, 'QuestionTypeId' )
                                                                )
                                                        ]
                                    ];
            }

            $type_name = $current_question->QuestionCategoryName;
            return view( "question.show", compact( 'current_question','question_types', 'question_categories', 'type_name', 'question_groups' ) );
        } else {
            return redirect('/question')->with( $response['success'], $response['message'] );
        }
    }

    /**
     * Store a newly or updated question in the data base
     * @return mixed  legal matter question or eligibility question listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_params =  [
        						'QuestionId'			=> request('qu_id'),
                                'QuestionLabel'         => filter_var(request('QuestionLabel'), FILTER_SANITIZE_STRING),
                                'QuestionName'         	=> filter_var(request('QuestionName'), FILTER_SANITIZE_STRING),
                                'QuestionTypeId'   		=> request('QuestionTypeId'),
                                'QuestionCategoryId'    => request('QuestionCategoryId'),
                                'QuestionGroupId'       => request('QuestionGroupId')
                            ];

        $question       = new Question();
        $response       = $question->saveQuestion( $question_params );
        $redirect_path  = '/';
        if(request('QuestionCategoryId') == LEGAL_MATTER_TYPE){
            $redirect_path .= LEGAL_MATTER_TYPE_URL;
        }
        if(request('QuestionCategoryId') == ELEGIBILITY_TYPE){
            $redirect_path .= ELEGIBILITY_TYPE_URL;
        }
        if(request('QuestionCategoryId') == BOOKING_TYPE){
            $redirect_path .= BOOKING_TYPE_URL;
        }
        return redirect( $redirect_path )->with( $response['success'], $response['message'] );
    }

    /**
     * Show the form for creating a new question
     * @param String $type question type
     * @return view question creation page
     */
    public function create( $type = '' )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_type_obj = new QuestionType();
        $question_types = $question_type_obj->getAllQuestionTypes();

        $question_category_obj = new QuestionCategory();
        $question_categories = $question_category_obj->getAllQuestionCategories();
        $type_name = '';
        if ( $type != '' ) {
            $question_categories = [
                                        $question_categories[
                                                                array_search(
                                                                        $type ,
                                                                        array_column( $question_categories, 'QuestionId' )
                                                                )
                                                            ]
                                    ];
            //Double check this functionality
            if ( $type == ELEGIBILITY_TYPE ) {
                $question_types = [
                                        $question_types[
                                                            array_search(
                                                                    LEGAL_MATTER_TYPE ,
                                                                    array_column( $question_types, 'QuestionTypeId' )
                                                                )
                                                        ]
                                    ];
            }

            $type_name = $question_categories[0]["QuestionName"];
        }

        return view( "question.create", compact( 'question_types', 'question_categories', 'type_name' ) );
    }

    /**
     * Remove the specified question from data base.
     * @param  int $qu_id question Id
     * @return mixed question listing page with success/error message
     */
    public function destroy( $qu_id )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question = new Question();
        $response = $question->deleteQuestion( $qu_id );

        return redirect('/question')->with( $response['success'], $response['message'] );
    }

    /**
     * List all question
     * @return array list of all question
     */
    public function list()
    {
        $question = new Question();
        $result = $question->getAllQuestions();
        return ['data' => $result];
    }

    /**
     * List all legal matter question
     * @return array list of all question
     */
    public function listLegalMatterQuestions()
    {
        $question = new Question();
        $result = $question->getAllLegalMatterQuestions();
        return ['data' => $result];
    }

    /**
     * List all legal matter question
     * @return array list of all question
     */
    public function listServiceBookingQuestions()
    {
        $question = new ServiceBookingQuestions();
        $result = $question->getAllServiceBookingQuestions();
        return ['data' => $result];
    }

    /**
     * List all eligibility question
     * @return array list of all eligibility question
     */
    public function listVulnerabilityQuestions()
    {
        $question = new Question();
        $result = $question->getAllVulnerabilityQuestions();
        return ['data' => $result];
    }
}