<?php
namespace App\Http;

use Auth;

class Helpers 
{

   public function VlaSimpleSAML()
   {
        $as = new SimpleSAML_Auth_Simple(env('SIMPLESML_SP'));
        $as->requireAuth();
        $attributes = $as->getAttributes();
        return $attributes;
    }

    public static function getRole()
    {
    	if( Auth::check() )
    	{
    		return Auth::user()->roles()->first()->name ;
    	} 
    	else
    	{
    		return 'Anonymous';
    	}
    }

    public static function getUSerServiceProviderId()
    {
    	if( Auth::check() )
    	{
    		return Auth::user()->sp_id ;
    	} 
    	else
    	{
    		return '';
    	}
    }
/**
     * Function taken from [http://php.net/manual/en/function.sort.php]
     * @param  [array] $array  [array to be sorted]
     * @param  [string] $on    [key to sort an specific array]
     * @param  [string] $order [name of constant that sorts according to sort functions ie. SORT_ASC | SORT_DESC]
     * @return [array]         [sorted array according to criteria]
     */
     public static function array_sort($array, $on, $order=SORT_ASC)
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

    
}