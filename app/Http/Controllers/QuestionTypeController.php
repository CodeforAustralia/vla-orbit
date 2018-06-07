<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuestionType;
use Auth;

/**
 * Question Type Controller.
 * Controller for the question Type functionalities
 * @author  Christian Arevalo
 * @version 1.2.0
 * @see  Controller
 */
class QuestionTypeController extends Controller
{
    /**
     * Question type contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of question type
     * @return view question type information
     */
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view( "question_type.index" );
    }

    /**
     * Display a specific question type
     * @param  int  $qt_id question type Id
     * @return view single question type information page
     */
    public function show( $qt_id )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_type = new QuestionType();
        $current_question_type = $question_type->getQuestionTypeById( $qt_id );

        return view("question_type.show", compact('current_question_type'));
    }

    /**
     * Store a newly or updated question type in the data base
     * @return mixed  question type listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_type_params = [
                                    'QuestionTypeId'	=> 0,
                                    'QuestionTypeName'  => request('QuestionTypeName'),
                                ];

        $question_type  = new QuestionType();
        $response       = $question_type->saveQuestionType( $question_type_params );

        return redirect('/question_type')->with( $response['success'], $response['message'] );
    }

    /**
     * Show the form for creating a new question type
     * @return view question type creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view( "question_type.create" );
    }

   /**
     * Remove the specified question type from data base.
     * @param  int $qt_id question type Id
     * @return mixed question type listing page with success/error message
     */
    public function destroy( $qt_id )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_type  = new QuestionType();
        $response       = $question_type->deleteQuestionType( $qt_id );

        return redirect('/question_type')->with( $response['success'], $response['message'] );
    }

    /**
     * List all question type
     * @return array list of all question type
     */
    public function list()
    {
        $question_type = new QuestionType();
        $result = $question_type->GetAllQuestionTypes();
        return ['data' => $result];
    }
}