<?php
namespace App;

Class QuestionGroup
{
    public $client;

    function __construct() 
    {
           $this->client = (new \App\Repositories\VlaSoap)->ws_init();
    }

    public function GetAllQuestionGroups()
    {
        $question_groups = json_decode($this->client->GetAllQuestionGroupsasJSON()->GetAllQuestionGroupsasJSONResult, true);

        return $question_groups;
    }
 
    public function getAllQuestionGroupsFormated()
    {
        $question_groups = self::GetAllQuestionGroups();
        $clean_groups = [];
        foreach ($question_groups as $group) {
            $clean_groups[] = [
                                    'id'         => $group['QuestionGroupId'], 
                                    'text'       => $group['GroupName'], 
                                    'ParentId'   => $group['ParentId'],  
                                    'GroupName'  => $group['GroupName'],
                                    'QuestionGroupId'   => $group['QuestionGroupId'],
                                ];
        }
        $groups = \App\Http\helpers::array_sort( $clean_groups, 'GroupName', SORT_ASC );        
        $output  = self::buildTree($groups) ;
        return $output;
    }

    public function getQuestionGroupById( $qg_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        try {
            $question = $client->GetQuestionGroupById( [ 'GroupId' => $qg_id  ] )->GetQuestionGroupByIdResult->QuestionGroup ;
            return array( 'success' => 'success' , 'message' => 'Question Group.', 'data' => $question );
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }

    }

    public function saveQuestionGroup( $question_group ) 
    {        
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $question_group['CreatedBy'] = auth()->user()->name;
        $question_group['UpdatedBy'] = auth()->user()->name;
        $question_group['CreatedOn'] = $date_time;
        $question_group['UpdatedOn'] = $date_time;

        // Create call request        
        $info = [ 'ObjectInstance' => $question_group ];
        
        try {
            $response = $this->client->SaveQuestionGroup( $info );
            
            if($response->SaveQuestionGroupResult){
                return array( 'success' => 'success' , 'message' => 'Question Group saved.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );      
        }
    }

    public function deleteQuestionGroup( $qg_id )
    {        
        // Create call request        
        $info = [ 'GroupId' => $qg_id ];

        try {
            $response = $this->client->DeleteQuestionGroup( $info );
            if($response->DeleteQuestionGroupResult){
                return array( 'success' => 'success' , 'message' => 'Question Group deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }
    
    public function GetAllQuestionGroupsTree()
    {
        $question_groups = self::GetAllQuestionGroups();
        $question_groups = \App\Http\helpers::array_sort( $question_groups, 'GroupName', SORT_ASC );
        $output  = self::buildTree($question_groups) ;
        return $output;
    }

    /**
     * Function taken from [https://stackoverflow.com/questions/13877656/php-hierarchical-array-parents-and-childs]
     * @param  array   $elements [parents and childs array or obj]
     * @param  integer $parentId [first parent]
     * @return array             [Tree of childs]
     */
    public function buildTree(array $elements, $parentId = 0) 
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['ParentId'] == $parentId) {
                $children = self::buildTree($elements, $element['QuestionGroupId']);
                if ($children) {
                    $element['children'] = $children;
                    if(isset($element['id'])){
                        unset($element['id']);
                    }
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }  
}