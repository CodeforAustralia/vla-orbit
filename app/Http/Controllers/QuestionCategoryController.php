<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuestionCategory;
use Auth;

/**
 * Question Category Controller.
 * Controller for the question category functionalities
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  Controller
 */
class QuestionCategoryController extends Controller
{
    /**
     * Question category contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of question category
     * @return view question category information
     */
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view( "question_category.index" );
    }

    /**
     * Display a specific question category
     * @param  int  $qc_id    question category Id
     * @return view single question category information page
     */
    public function show( $qc_id )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_category = new QuestionCategory();
        $current_question_category = $question_category->getQuestionCategoryById( $qt_id );

        return view( "question_category.show", compact('current_question_category') );
    }

    /**
     * Store a newly or updated question category in the data base
     * @return mixed  question category listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_category_params = [
                                        'QuestionId'	=> 0,
                                        'QuestionName'  => request('QuestionName'),
                                    ];

        $question_category  = new QuestionCategory();
        $response           = $question_category->saveQuestionCategory( $question_category_params );

        return redirect('/question_category')->with( $response['success'], $response['message'] );
    }

    /**
     * Show the form for creating a new question category
     * @return view question category creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view( "question_category.create" );
    }

    /**
     * Remove the specified question category from data base.
     * @param  int $qc_id question category Id
     * @return mixed question category listing page with success/error message
     */
    public function destroy( $qc_id )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_category  = new QuestionCategory();
        $response       = $question_category->deleteQuestionCategory( $qc_id );

        return redirect('/question_category')->with( $response['success'], $response['message'] );
    }

    /**
     * List all question category
     * @return array list of all question category
     */
    public function list()
    {
        $question_category = new QuestionCategory();
        $result = $question_category->GetAllQuestionCategories();
        return ['data' => $result];
    }
}