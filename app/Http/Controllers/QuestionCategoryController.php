<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuestionCategory;

class QuestionCategoryController extends Controller
{
    
    public function index()
    {
        return view( "question_category.index" );
    }

    public function show( $qc_id )
    {

        $question_category = new QuestionCategory();
        $current_question_category = $question_category->getQuestionCategoryById( $qt_id );

        return view( "question_category.show", compact('current_question_category') );
    }

    public function store()
    {        
        $question_category_params =    array(
                    						'QuestionId'	=> 0,
                                            'QuestionName'  => request('QuestionName'),
                                        );
        
        $question_category  = new QuestionCategory();
        $response           = $question_category->saveQuestionCategory( $question_category_params );
        
        return redirect('/question_category')->with( $response['success'], $response['message'] );
    }
    
    public function create()
    {

        return view( "question_category.create" );
    }

    public function destroy( $qc_id )
    {        
        $question_category  = new QuestionCategory();
        $response       = $question_category->deleteQuestionCategory( $qc_id );
        
        return redirect('/question_category')->with( $response['success'], $response['message'] );
    }

    public function list()
    {
        $question_category = new QuestionCategory();
        $result = $question_category->GetAllQuestionCategories();
        return array( 'data' => $result );
    }
}