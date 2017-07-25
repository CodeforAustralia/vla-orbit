<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuestionType;
use Auth;

class QuestionTypeController extends Controller
{
    public function __construct()
    {       
        $this->middleware('auth');
    }

    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view( "question_type.index" );
    }

    public function show( $qt_id )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_type = new QuestionType();
        $current_question_type = $question_type->getQuestionTypeById( $qt_id );

        return view("question_type.show", compact('current_question_type'));
    }

    public function store()
    {        
        Auth::user()->authorizeRoles('Administrator');

        $question_type_params =    array(
                    						'QuestionTypeId'	=> 0,
                                            'QuestionTypeName'  => request('QuestionTypeName'),
                                        );
        
        $question_type  = new QuestionType();
        $response       = $question_type->saveQuestionType( $question_type_params );
        
        return redirect('/question_type')->with( $response['success'], $response['message'] );
    }
    
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view( "question_type.create" );
    }

    public function destroy( $qt_id )
    {        
        Auth::user()->authorizeRoles('Administrator');
        
        $question_type  = new QuestionType();
        $response       = $question_type->deleteQuestionType( $qt_id );
        
        return redirect('/question_type')->with( $response['success'], $response['message'] );
    }

    public function list()
    {
        $question_type = new QuestionType();
        $result = $question_type->GetAllQuestionTypes();
        return array( 'data' => $result );
    }
}