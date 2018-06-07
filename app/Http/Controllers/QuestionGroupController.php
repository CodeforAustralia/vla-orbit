<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuestionGroup;
use Auth;

/**
 * Question Group Controller.
 * Controller for the question group functionalities
 * @author  Christian Arevalo
 * @version 1.2.0
 * @see  Controller
 */
class QuestionGroupController extends Controller
{
    /**
     * Question group contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of question group
     * @return view question group information
     */
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view( "question_group.index" );
    }

    /**
     * Display a specific question group
     * @param  integer  $qg_id    question group Id
     * @return view single question group information page
     */
    public function show( $qg_id )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_group = new QuestionGroup();
        $current_question_group = $question_group->getQuestionGroupById( $qg_id )['data'];
        $question_groups = $question_group->GetAllQuestionGroups();

        return view("question_group.show", compact('current_question_group', 'question_groups'));
    }

    /**
     * Store a newly or updated question group in the data base
     * @return mixed  question group listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_group_params =    [
                                        'QuestionGroupId'	=> request('QuestionGroupId'),
                                        'GroupName'         => request('GroupName'),
                                        'ParentId'          => request('ParentId'),
                                    ];

        $question_group  = new QuestionGroup();
        $response       = $question_group->saveQuestionGroup( $question_group_params );

        return redirect('/question_group')->with( $response['success'], $response['message'] );
    }

    /**
     * Show the form for creating a new question group
     * @return view question type creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_group = new QuestionGroup();
        $question_groups = $question_group->GetAllQuestionGroups();
        return view( "question_group.create", compact('question_groups') );
    }

    /**
     * Remove the specified question group from data base.
     * @param  integer $qg_id question group Id
     * @return mixed question group listing page with success/error message
     */
    public function destroy( $qg_id )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_group = new QuestionGroup();
        $response       = $question_group->deleteQuestionGroup( $qg_id );

        return redirect('/question_group')->with( $response['success'], $response['message'] );
    }

    /**
     * List all question group
     * @return array list of all question group
     */
    public function list()
    {
        $question_group = new QuestionGroup();
        $result = $question_group->GetAllQuestionGroups();
        return ['data' => $result];
    }

    /**
     * List all question group formated
     * @return array list of all question group formated
     */
    public function listFormated()
    {
        $question_group = new QuestionGroup();
        $result = $question_group->getAllQuestionGroupsFormated();
        return ['data' => $result];
    }
}