<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Matter;
use App\MatterType;
use App\Question;
use App\MatterQuestion;

class MatterController extends Controller
{    
    public function __construct()
    {       
        $this->middleware('auth');
    }

    public function index()
    {
        return view("matter.index");
    }

    public function show( Request $request, $m_id )
    {
        $request->user()->authorizeRoles('Administrator');

        $matter_type_obj = new MatterType();
        $matter_types    = $matter_type_obj->getAllMatterTypes();        

        $matter  = new Matter();
        $matters = $matter->getAllMatters();

        $current_matter = $matter->getAllMatterById( $m_id );

        $questions_obj = new Question();
        $questions     = $questions_obj->getAllQuestionsByCategoryID( 1 ); //Common Questions

        return view( "matter.show", compact( 'current_matter', 'matters', 'matter_types', 'questions' ) );
    }

    public function store()
    {        
        $request->user()->authorizeRoles('Administrator');
        
        $matter_params =    array(
                                'MatterID'     => request('MatterID'),
                                'MatterName'   => request('title'),
                                'Description'  => request('description'),
                                'ParentId'     => request('parent_id'),
                                'Tag'          => request('tag'),
                                'TypeId'       => request('lmt_id')
                            );
        
        $matter = new Matter();
        $response = $matter->saveMatter($matter_params);

        if( request('MatterID') > 0 || isset( $response['data'] ) ) {
            $mt_id = ( request('MatterID') > 0 ? request('MatterID') : $response['data'] );
            $matter_question = new MatterQuestion();            
            $matter_question->processMatterQuestions( request('question'), $mt_id );
        }
        
        return redirect('/matter')->with($response['success'], $response['message']);
    }
    
    public function create( Request $request )
    {
        $request->user()->authorizeRoles('Administrator');

        $matter_type_obj = new MatterType();
        $matter_types = $matter_type_obj->getAllMatterTypes();


        $matter_obj = new Matter();
        $matters = $matter_obj->getAllMatters();

        $questions_obj = new Question();
        $questions     = $questions_obj->getAllQuestionsByCategoryID( 1 ); //Common Questions

        return view( "matter.create", compact( 'matter_types', 'matters', 'questions' ) );
    }

    public function destroy( Request $request, $m_id )
    {        
        $request->user()->authorizeRoles('Administrator');

        $matter = new Matter();
        $response = $matter->deleteMatter( $m_id );
        
        return redirect('/matter')->with( $response['success'], $response['message'] );
    }

    public function list()
    {
        $matter = new Matter();
        $result = $matter->getAllMatters();
        return array( 'data' => $result );
    }

    public function listFormated()
    {
        $matter = new Matter();
        $result = $matter->getAllMattersFormated();
        return $result;
    }
}