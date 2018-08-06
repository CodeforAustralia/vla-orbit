<?php
namespace App\Http;

use Auth;
use DateTime;

/**
 * Common functionalities.
 *
 * @author Christian Arevalo and Sebastian Currea
 * @version 1.0.1

 */
class Helpers
{
    /**
     * Get simpleSAML attributes
     * @return array attributes
     */
    public function VlaSimpleSAML()
    {
        $as = new SimpleSAML_Auth_Simple(env('SIMPLESML_SP'));
        $as->requireAuth();
        $attributes = $as->getAttributes();
        return $attributes;
    }
    /**
     * Get session user role
     * @return String role
     */
    public static function getRole()
    {
    	if( Auth::check() ) {
    		return Auth::user()->roles()->first()->name ;
    	} else {
    		return 'Anonymous';
    	}
    }
    /**
     * Get service provider from the user in session
     * @return String service provider id
     */
    public static function getUSerServiceProviderId()
    {
    	if( Auth::check() )	{
    		return Auth::user()->sp_id ;
    	} else {
    		return '';
    	}
    }
    /**
     * Get session user id
     * @return String user id
     */
    public static function getUSerId()
    {
    	if( Auth::check() ) {
    		return Auth::user()->id ;
    	} else {
    		return '';
    	}
    }
    /**
     * Retrieve the panel lawyers exluded
     * @return array excluded panel lawyers.
     */
    public static function getPanelLawyersRemoveList()
    {
        return ['29545', 'F4190', '28708', 'F6313', '28707', '3256', '26271', '26270', '26300', '2366'];
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

    /**
     * Check if today is the first friday of the month
     *
     * @return boolean
     */
    public static function firstFridayOfMonth() {
        $today = new DateTime();
        $first_friday = new DateTime('first friday of this month');
        return $today->format('d-m-y') == $first_friday->format('d-m-y');
    }
}