<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\QuestionType;
use App\QuestionCategory;

class QuestionController extends Controller
{
    
    public function index()
    {
        return view("question.index");
    }

    public function legalMatterQuestions()
    {
        return view("question.legal_matter_questions");
    }
    
    public function eligibilityCriteria()
    {
        return view("question.eligibility_criteria");
    }

    public function show( $qu_id )
    {
        $question_type_obj = new QuestionType();
        $question_types = $question_type_obj->getAllQuestionTypes();

        $question_category_obj = new QuestionCategory();
        $question_categories = $question_category_obj->getAllQuestionCategories();

        $question = new Question();
        $current_question = $question->getAllQuestionById( $qu_id );

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
            return view( "question.show", compact( 'current_question','question_types', 'question_categories', 'type_name' ) );         
        } else {
            return redirect('/question')->with( $response['success'], $response['message'] );
        }    
    }

    public function store()
    {        
        $question_params =    array(
        						'QuestionId'			=> request('qu_id'),
                                'QuestionName'         	=> request('QuestionName'),
                                'QuestionTypeId'   		=> request('QuestionTypeId'),
                                'QuestionCategoryId'    => request('QuestionCategoryId'),
                            );
        
        $question       = new Question();
        $response       = $question->saveQuestion( $question_params );
        $redirect_path  = ( request('QuestionCategoryId') == 2 ? '/eligibility_criteria' : '/legal_matter_questions');
        return redirect( $redirect_path )->with( $response['success'], $response['message'] );
    }
    
    public function create( $type = '' )
    {
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