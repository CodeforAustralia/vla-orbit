<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Matter;
use App\MatterType;
use App\Question;
use App\MatterQuestion;

/**
 * Matter Controller.
 * Controller for the legal matter functionalities
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  Controller
 */
class MatterController extends Controller
{
    /**
     * Matter contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of legal matter
     * @return view legal matter information
     */
    public function index()
    {
        return view("matter.index");
    }

    /**
     * Display a specific legal matter
     * @param  Request $request
     * @param  integer  $m_id    Legal matter Id
     * @return view single Legal matter information page
     */
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
    /**
     * Store a newly or updated legal matter in the data base
     * @param  Request $request
     * @return mixed  legal matter listing page with success/error message
     */
    public function store( Request $request )
    {
        $request->user()->authorizeRoles('Administrator');

        $matter_params = [
                            'MatterID'     => request('MatterID'),
                            'MatterName'   => filter_var(request('title'), FILTER_SANITIZE_STRING),
                            'Description'  => filter_var(request('description'), FILTER_SANITIZE_STRING),
                            'ParentId'     => request('parent_id'),
                            'Tag'          => filter_var(request('tag'), FILTER_SANITIZE_STRING),
                            'TypeId'       => request('lmt_id')
                         ];

        $matter = new Matter();
        $response = $matter->saveMatter($matter_params);

        if( request('MatterID') > 0 || isset( $response['data'] ) ) {
            $mt_id = ( request('MatterID') > 0 ? request('MatterID') : $response['data'] );
            $matter_question = new MatterQuestion();
            $matter_question->processMatterQuestions( request('question'), $mt_id );
        }

        return redirect('/matter')->with($response['success'], $response['message']);
    }
    /**
     * Show the form for creating a new legal matter
     * @param  Request $request
     * @return view legal matter creation page
     */
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
    /**
     * Remove the specified legal matter from data base.
     * @param  int $m_id legal matter Id
     * @param  Request $request
     * @return mixed legal matter listing page with success/error message
     */
    public function destroy( Request $request, $m_id )
    {
        $request->user()->authorizeRoles('Administrator');

        $matter = new Matter();
        $response = $matter->deleteMatter( $m_id );

        return redirect('/matter')->with( $response['success'], $response['message'] );
    }
    /**
     * List all legal matters
     * @return array list of all legal matters
     */
    public function list()
    {
        $matter = new Matter();
        $result = $matter->getAllMatters();
        return ['data' => $result];
    }
    /**
     * List all legal matters trimmed
     * @return array list of all legal matters trimmed
     */
    public function listFormatedTrimmed()
    {
        $matter = new Matter();
        $result = $matter->getMattersDatasetTrimmed();
        return $result;
    }
    /**
     * List all legal matters formated
     * @return array list of all legal matters formated
     */
    public function listFormated()
    {
        $matter = new Matter();
        $result = $matter->getAllMattersFormated();
        return $result;
    }
    public function listWithQuestionsFormated()
    {
        $matter = new Matter();
        $result = $matter->getAllMattersWithQuestionsFormated();
        return $result;
    }
}