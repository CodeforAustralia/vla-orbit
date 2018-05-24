<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuestionGroup;
use Auth;

class QuestionGroupController extends Controller
{
    public function __construct()
    {       
        $this->middleware('auth');
    }

    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view( "question_group.index" );
    }

    public function show( $qg_id )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_group = new QuestionGroup();
        $current_question_group = $question_group->getQuestionGroupById( $qg_id )['data'];
        $question_groups = $question_group->GetAllQuestionGroups();
//dd($current_question_group);
        return view("question_group.show", compact('current_question_group', 'question_groups'));
    }

    public function store()
    {        
        Auth::user()->authorizeRoles('Administrator');

        $question_group_params =    array(
                    						'QuestionGroupId'	=> request('QuestionGroupId'),
                                            'GroupName'         => request('GroupName'),
                                            'ParentId'          => request('ParentId'),
                                        );
        
        $question_group  = new QuestionGroup();
        $response       = $question_group->saveQuestionGroup( $question_group_params );
        
        return redirect('/question_group')->with( $response['success'], $response['message'] );
    }
    
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_group = new QuestionGroup();
        $question_groups = $question_group->GetAllQuestionGroups();
        return view( "question_group.create", compact('question_groups') );
    }

    public function destroy( $qg_id )
    {        
        Auth::user()->authorizeRoles('Administrator');
        
        $question_group  = new QuestionGroup();
        $response       = $question_group->deleteQuestionGroup( $qg_id );
        
        return redirect('/question_group')->with( $response['success'], $response['message'] );
    }

    public function list()
    {
        $question_group = new QuestionGroup();
        $result = $question_group->GetAllQuestionGroups();
        return array( 'data' => $result );
    }

    public function listFormated()
    {
        $question_group = new QuestionGroup();
        $result = $question_group->getAllQuestionGroupsFormated();
        return array( 'data' => $result );
    }
}