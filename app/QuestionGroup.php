<?php
namespace App;

/**
 * Question Group Model.
 *
 * @author  Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */
Class QuestionGroup extends OrbitSoap
{
    /**
     * Get all Question Groups
     *
     * @return array Array with Question Groups
     */
    public function GetAllQuestionGroups()
    {
        $question_groups = json_decode($this
                                       ->client
                                       ->ws_init('GetAllQuestionGroupsasJSON')
                                       ->GetAllQuestionGroupsasJSON()
                                       ->GetAllQuestionGroupsasJSONResult, true);

        return $question_groups;
    }

    /**
     * Get All Question Groups in format id, text, parentID to be used in a select2 field
     *
     * @return array Array of Question Groups
     */
    public function getAllQuestionGroupsFormated()
    {
        $question_groups = self::GetAllQuestionGroups();
        $clean_groups = [];
        foreach ($question_groups as $group) {
            $clean_groups[] =   [
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

    /**
     * Get Question Group by ID
     *
     * @param integer $qg_id Question Group ID
     * @return array Array with error or success message
     */
    public function getQuestionGroupById( $qg_id )
    {
        try {
            $question = $this
                        ->client
                        ->ws_init('GetQuestionGroupById')
                        ->GetQuestionGroupById( [ 'GroupId' => $qg_id  ] )
                        ->GetQuestionGroupByIdResult
                        ->QuestionGroup ;
            return ['success' => 'success' , 'message' => 'Question Group.', 'data' => $question];
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }

    }

    /**
     * Save Question Group
     *
     * @param array $question_group Array with Question Group information
     * @return array Array with error or success message
     */
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
            $response = $this
                        ->client
                        ->ws_init('SaveQuestionGroup')
                        ->SaveQuestionGroup( $info );

            if ($response->SaveQuestionGroupResult) {
                return ['success' => 'success' , 'message' => 'Question Group saved.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Delete Question Group by ID
     *
     * @param integer $qg_id Question Group ID
     * @return array Array with error or success message
     */
    public function deleteQuestionGroup( $qg_id )
    {
        // Create call request
        $info = [ 'GroupId' => $qg_id ];

        try {
            $response = $this
                        ->client
                        ->ws_init('DeleteQuestionGroup')
                        ->DeleteQuestionGroup( $info );
            if ($response->DeleteQuestionGroupResult) {
                return ['success' => 'success' , 'message' => 'Question Group deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Get Question Groups in a tree with parent child relation and sorted alphabetically
     *
     * @return array  Array of Question Groups
     */
    public function GetAllQuestionGroupsTree()
    {
        $question_groups = self::GetAllQuestionGroups();
        $question_groups = \App\Http\helpers::array_sort( $question_groups, 'GroupName', SORT_ASC );
        $output  = self::buildTree($question_groups);

        return $output;
    }

    /**
     * Function taken from [https://stackoverflow.com/questions/13877656/php-hierarchical-array-parents-and-childs]
     * @param  array   $elements parents and childs array or obj
     * @param  integer $parentId first parent
     * @return array             Tree of childs
     */
    public function buildTree(array $elements, $parentId = 0)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['ParentId'] == $parentId) {
                $children = self::buildTree($elements, $element['QuestionGroupId']);
                if ($children) {
                    $element['children'] = $children;
                    if (isset($element['id'])) {
                        unset($element['id']);
                    }
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
}