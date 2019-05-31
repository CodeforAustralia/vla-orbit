<?php
namespace App;

/**
 * Legal Matter Model.
 * Model for the legal matter functionalities
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */

const SPECIFIC_MATTER = 7;

Class Matter extends OrbitSoap
{
    /**
     * Get all Legal matters
     *
     * @return array Array of legal matters
     */
    public function getAllMatters()
    {
        $matters = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetAllLegalMattersasJSON')
                                    ->GetAllLegalMattersasJSON()
                                    ->GetAllLegalMattersasJSONResult
                                    , true
                                );
        array_shift($matters);
        return $matters;
    }

    /**
     * Get all Legal matters with questions
     *
     * @return array Array of legal matters
     */
    public function getAllMattersWithQuestions()
    {
        $matters = json_decode(
                                    $this
                                    ->client
                                    ->ws_init_local('GetAllMattersWithQuestionsasJSON')
                                    ->GetAllMattersWithQuestionsasJSON()
                                    ->GetAllMattersWithQuestionsasJSONResult
                                    , true
                                );
        return $matters;
    }

    /**
     * Get all Legal matters in format id, text to be used in a select2 field
     *
     * @return array Array of legal matters
     */
    public function getAllMattersFormated()
    {
        $matters = self::getAllMatters();

        $output = [];
        foreach ($matters as $matter) {
            if ( $matter['TypeId'] == SPECIFIC_MATTER ) {
                $output[] = [
                            'id'    => $matter['MatterID'],
                            'text'  => self::getParent( $matters, $matter['MatterID'] )
                            ];
            }
        }

        return $output;
    }

    /**
     * Get all Legal matters in format id, text to be used in a select2 field
     *
     * @return array Array of legal matters
     */
    public function getAllMattersWithQuestionsFormated()
    {
        $matters_questions = self::getAllMattersWithQuestions();
        $matters = self::getAllMatters();

        $output = [];
        foreach ($matters_questions as $matter) {
            if ( $matter['TypeId'] == SPECIFIC_MATTER ) {
                $output[] = [
                            'id'        => $matter['MatterID'],
                            'text'      => self::getParent( $matters, $matter['MatterID'] ),
                            'questions' => $matter['MatterQuestions']
                        ];
            }
        }
        return $output;
    }

    /**
     * Recursive function to sort by parent of each Legal matter starting from an specific parent
     *
     * @param array $matters Array of Legal matters
     * @param integer $mt_id Legal matter ID
     * @return array Array with sorted Legal matters and parents
     */
    public function getParent( $matters, $mt_id )
    {
        foreach ( $matters as $matter ) {

            if ( $matter['MatterID'] == $mt_id && $matter['ParentId'] > 0 ) {

                $parent = self::getParent( $matters, $matter['ParentId'] );

                if ( $parent ) {
                    return  $parent . " - " . $matter['MatterName'];
                } else {
                    return $matter['MatterName'];
                }
            }
        }
    }

    /**
     * Function used by search page on referrals page in format id, text, parent.... to be used in a select2 field
     * @return array Array of legal matters with parent child relation
    */
    public function getAllMattersParentChildrenListTrimmed()
    {
        $matters = self::getAllMatters();
        $clean_matters = [];
        foreach ($matters as $matter) {
            $clean_matters[] = [
                                    'id'         => $matter['MatterID'],
                                    'text'       => $matter['MatterName'],
                                    'ParentId'   => $matter['ParentId'],
                                    'ParentName' => $matter['ParentName'],
                                    'MatterName' => $matter['MatterName'],
                                    'MatterID'   => $matter['MatterID'],
                                    'Tag'        => $matter['Tag'],
                                ];
        }

        $matters = \App\Http\helpers::array_sort( $clean_matters, 'MatterName', SORT_ASC );
        $output  = self::buildTree($matters, 50) ;

        return $output;
    }

    /**
     * Function used by search function that select2 uses in just 2 leves, this because more levels are not allowed
     * @return array Array of matters in 2 leveles, sub groups are disable options wich are just above each specific matter
     */
    public function getMattersDatasetTrimmed()
    {
        $matters = self::getAllMattersParentChildrenListTrimmed();
        $dataset = [];

        foreach ( $matters as $key => $matter ) {

            //Copy matters removing their childrens and store it in the dataset
            $m_copy = $matter;
            unset($m_copy['children']);
            $dataset[$key] = $m_copy;

            if ( isset( $matter['children'] ) ) {

                $pos = 0;
                foreach ( $matter['children'] as $first_child ) {
                    //For each subgroup of legal matters get the name and make it disable to select on plugin, un less is an specific issue of level 2
                    if ( isset( $first_child['children'] ) ) {

                        //Copy subgroup removing their childrens and store it in the dataset
                        $fc_copy = $first_child;
                        $fc_copy['disabled'] = true; //Disable to select on plugin
                        $fc_copy['Tag'] .= ', ' . $matter['text']; //Add all specific matters to the sub group tags/keywords

                        unset($fc_copy['children']);
                        $parent_post = $pos;
                        $dataset[$key]['children'][$pos] = $fc_copy;

                        $current_child_tags = explode(',', $dataset[$key]['children'][$parent_post]['Tag']);

                        foreach ( $first_child['children'] as $second_child ) {
                            //Each specific matter should be under each sub group title in the options with their own tag
                            $pos++;

                            if ( !isset( $second_child['children'] ) ) {
                                $dataset[$key]['children'][$pos] = $second_child;
                            }
                            //Add all specific matters to the parent sub group tags/keywords
                            $second_child_tags = explode(',', $second_child['Tag'] . ', ' . $second_child['text'] );
                            $current_child_tags = array_unique (array_merge ($current_child_tags, $second_child_tags));

                        }
                        $dataset[$key]['children'][$parent_post]['Tag'] =  implode(',', $current_child_tags);

                    } else {
                        $dataset[$key]['children'][$pos] = $first_child;
                    }
                    $pos++;
                }
            }

        }

        array_pop( $dataset );

        return array_reverse($dataset);
    }

    /**
     * Get all Legal matters with parent and childrens sorted alphabetically
     *
     * @return array Sorted Legal matters
     */
    public function getAllMattersParentChildrenList()
    {
        $matters = self::getAllMatters();
        $matters = \App\Http\helpers::array_sort( $matters, 'MatterName', SORT_ASC );
        $output  = self::buildTree($matters, 50) ;
        return $output;
    }

    /**
     * Function taken from [https://stackoverflow.com/questions/13877656/php-hierarchical-array-parents-and-childs]
     * @param  array   $elements parents and childs array or obj
     * @param  integer $parentId first parent
     * @return array  Tree of childs
     */
    public function buildTree(array $elements, $parentId = 0)
    {
            $branch = [];

            foreach ($elements as $element) {
                if ($element['ParentId'] == $parentId) {
                    $children = self::buildTree($elements, $element['MatterID']);
                    if ($children) {
                        $element['children'] = $children;
                        if (isset($element['id'])){
                            unset($element['id']);
                        }
                    }
                    $branch[] = $element;
                }
            }

            return $branch;
    }

    /**
     * Get Legal Matter by ID
     *
     * @param integer $mt_id Legal matter ID
     * @return array Array containing Legal matters with questions
     */
    public function getAllMatterById( $mt_id )
    {
        $matter = $this
                  ->client
                  ->ws_init('GetMattersById')
                  ->GetMattersById( ['MatterId' => $mt_id] )
                  ->GetMattersByIdResult
                  ->LegalMatter;
        if ( isset( $matter->MatterQuestions->LegalMatterQuestions ) ) {

            $questions = [];
            // Change it to avoid 7.2 php error. Check if is array or not
            if (isset($matter->MatterQuestions->LegalMatterQuestions->QuestionId)) {

                $questionId = $matter->MatterQuestions->LegalMatterQuestions->QuestionId;
                $operator = $matter->MatterQuestions->LegalMatterQuestions->Operator;
                $value = $matter->MatterQuestions->LegalMatterQuestions->QuestionValue;
                $questions[ $questionId ] = [ 'operator' => $operator , 'value' => $value ];
                $matter->MatterQuestions = $questions;
            } else {

                foreach ($matter->MatterQuestions->LegalMatterQuestions as $question) {
                    $questions[ $question->QuestionId ] = [ 'operator' => $question->Operator , 'value' => $question->QuestionValue ];
                }
                $matter->MatterQuestions = $questions;
            }
        }
        return $matter;
    }

    /**
     * Save Legal matter
     *
     * @param array $matter Array with Legal matter information
     * @return array Array with error or success message
     */
    public function saveMatter( $matter )
    {
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $matter['CreatedBy'] = auth()->user()->name;
        $matter['UpdatedBy'] = auth()->user()->name;
        $matter['CreatedOn'] = $date_time;
        $matter['UpdatedOn'] = $date_time;

        $info = [ 'ObjectInstance' => $matter ];

        try {
            $response = $this->client->ws_init('SaveMatter')->SaveMatter( $info );

            if ( $response->SaveMatterResult ) {
                return ['success' => 'success' , 'message' => 'Legal matter saved.', 'data' => $response->SaveMatterResult];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Delete Legal matter by ID
     *
     * @param integer $m_id Legal matter ID
     * @return array Array with error or success message
     */
    public function deleteMatter( $m_id )
    {
        $info = [ 'RefNumber' => $m_id];

        try {
            $response = $this->client->ws_init('DeleteMatter')->DeleteMatter($info);

            if ($response->DeleteMatterResult) {
                return ['success' => 'success' , 'message' => 'Legal matter deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }
}

