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
            return view( "question.show", compact( 'current_question','question_types', 'question_categories' ) );         
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
        
        $question = new Question();
        $response = $question->saveQuestion( $question_params );
        
        return redirect('/question')->with( $response['success'], $response['message'] );
    }
    
    public function create()
    {
        $question_type_obj = new QuestionType();
        $question_types = $question_type_obj->getAllQuestionTypes();

        $question_category_obj = new QuestionCategory();
        $question_categories = $question_category_obj->getAllQuestionCategories();

        return view( "question.create", compact( 'question_types', 'question_categories' ) );
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
}