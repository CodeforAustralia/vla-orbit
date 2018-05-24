<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\QuestionGroup;
use App\QuestionType;
use App\QuestionCategory;
use Auth;

class QuestionController extends Controller
{
    public function __construct()
    {       
        $this->middleware('auth');
    }

    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("question.index");
    }

    public function legalMatterQuestions()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("question.legal_matter_questions");
    }
    
    public function eligibilityCriteria()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("question.eligibility_criteria");
    }

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

        if(isset($current_question['data'])) {
            $current_question = $current_question['data'][0];
            $type_name = '';
            if( $current_question->QuestionCategoryId == 2 ) //Eligibility type 
            {
                $question_types = [ $question_types[ array_search( 
                                                                    1 , //Boolean type id
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

    public function store()
    {        
        Auth::user()->authorizeRoles('Administrator');

        $question_params =    array(
        						'QuestionId'			=> request('qu_id'),
                                'QuestionLabel'         => filter_var(request('QuestionLabel'), FILTER_SANITIZE_STRING),
                                'QuestionName'         	=> filter_var(request('QuestionName'), FILTER_SANITIZE_STRING),
                                'QuestionTypeId'   		=> request('QuestionTypeId'),
                                'QuestionCategoryId'    => request('QuestionCategoryId'),
                                'QuestionGroupId'    => request('QuestionGroupId')
                            );
        
        $question       = new Question();
        $response       = $question->saveQuestion( $question_params );
        $redirect_path  = ( request('QuestionCategoryId') == 2 ? '/eligibility_criteria' : '/legal_matter_questions');
        return redirect( $redirect_path )->with( $response['success'], $response['message'] );
    }
    
    public function create( $type = '' )
    {
        Auth::user()->authorizeRoles('Administrator');

        $question_type_obj = new QuestionType();
        $question_types = $question_type_obj->getAllQuestionTypes();

        $question_category_obj = new QuestionCategory();
        $question_categories = $question_category_obj->getAllQuestionCategories();
        $type_name = '';
        if( $type != '' )
        {
            $question_categories = [ $question_categories[ array_search( 
                                                                        $type , 
                                                                        array_column( $question_categories, 'QuestionId' ) 
                                                                      ) 
                                                       ]
                                    ];
            if( $type == 2 ) //Eligibility type 
            {
                $question_types = [ $question_types[ array_search( 
                                                                    1 , //Boolean type id
                                                                    array_column( $question_types, 'QuestionTypeId' ) 
                                                                  ) 
                                                       ]
                                    ];                
            }
                                    
            $type_name = $question_categories[0]["QuestionName"];
        }

        return view( "question.create", compact( 'question_types', 'question_categories', 'type_name' ) );
    }

    public function destroy( $qu_id )
    {        
        Auth::user()->authorizeRoles('Administrator');
        
        $question = new Question();
        $response = $question->deleteQuestion( $qu_id );
        
        return redirect('/question')->with( $response['success'], $response['message'] );
    }

    public function list()
    {
        $question = new Question();
        $result = $question->getAllQuestions();
        return array( 'data' => $result );
    }

    public function listLegalMatterQuestions()
    {
        $question = new Question();
        $result = $question->getAllLegalMatterQuestions();
        return array( 'data' => $result );
    }

    public function listVulnerabilityQuestions()
    {
        $question = new Question();
        $result = $question->getAllVulnerabilityQuestions();
        return array( 'data' => $result );
    }
}