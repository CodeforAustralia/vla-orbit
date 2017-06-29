<?php
namespace App;

Class Matter
{
    public function getAllMatters()
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        $matters = json_decode($client->GetAllLegalMattersasJSON()->GetAllLegalMattersasJSONResult, true);

        return $matters;
    }

    public function getAllMattersFormated()
    {
        $matters = self::getAllMatters();

        $output = [];

        foreach ($matters as $matter) {
            if( $matter['TypeId'] == 7 ) //Specific matter
            {                
                $output[] = [
                            'id'    => $matter['MatterID'],
                            'text'  => self::getParent( $matters, $matter['MatterID'] )
                            ];
            }
        }

        return $output;
    }

    public function getParent( $matters, $mt_id )
    {        
        foreach ( $matters as $matter ) 
        {
            if( $matter['MatterID'] == $mt_id && $matter['ParentId'] > 0 ) 
            {         
                $parent = self::getParent( $matters, $matter['ParentId'] );
                if( $parent )
                {
                    return  $parent . " - " . $matter['MatterName'];
                } else 
                {
                    return $matter['MatterName'];
                }
            } 
        }
    }

    public function getAllMattersParentChildrenList()
    {
        $matters = self::getAllMatters();
        $matters = self::array_sort( $matters, 'MatterName', SORT_ASC );        
        $output  = self::buildTree($matters, 50) ;
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
                    $children = self::buildTree($elements, $element['MatterID']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                }
            }

            return $branch;
    }  

    /**
     * Function taken from [http://php.net/manual/en/function.sort.php]
     * @param  [array] $array  [array to be sorted]
     * @param  [string] $on    [key to sort an specific array]
     * @param  [string] $order [name of constant that sorts according to sort functions ie. SORT_ASC | SORT_DESC]
     * @return [array]         [sorted array according to criteria]
     */
    public function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public function getAllMatterById( $mt_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        $matter = $client->GetMattersById( array( 'MatterId' => $mt_id ) )->GetMattersByIdResult->LegalMatter;
        if( isset( $matter->MatterQuestions->LegalMatterQuestions ) )
        {
            $questions = [];
            if(sizeof($matter->MatterQuestions->LegalMatterQuestions) == 1)
            {
                $questionId = $matter->MatterQuestions->LegalMatterQuestions->QuestionId;
                $operator = $matter->MatterQuestions->LegalMatterQuestions->Operator;
                $value = $matter->MatterQuestions->LegalMatterQuestions->QuestionValue;
                $questions[ $questionId ] = [ 'operator' => $operator , 'value' => $value ];
                $matter->MatterQuestions = $questions; 
            } else 
            {
                foreach ($matter->MatterQuestions->LegalMatterQuestions as $question) {
                    $questions[ $question->QuestionId ] = [ 'operator' => $question->Operator , 'value' => $question->QuestionValue ];
                }
                $matter->MatterQuestions = $questions;                
            }
        }
        
        return $matter;

    }

    public function saveMatter( $matter ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Current time     
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $matter['CreatedBy'] = auth()->user()->name;     
        $matter['UpdatedBy'] = auth()->user()->name;     
        $matter['CreatedOn'] = $date_time;       
        $matter['UpdatedOn'] = $date_time;    

        // Create call request        
        $info = [ 'ObjectInstance' => $matter ];
        
        try {

            $response = $client->SaveMatter( $info );

            if( $response->SaveMatterResult ){
                return array( 'success' => 'success' , 'message' => 'New legal matter created.', 'data' => $response->SaveMatterResult );
            } else {
                return array( 'success' => 'error' , 'message' => 'something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );      
        }
    }

    public function deleteMatter( $m_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'RefNumber' => $m_id];

        try {
            $response = $client->DeleteMatter($info);
            if($response->DeleteMatterResult){
                return array( 'success' => 'success' , 'message' => 'Legal matter deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }    
}

