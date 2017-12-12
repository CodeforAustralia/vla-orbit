<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Auth;

class Log extends Model
{
    /**
     * Record interactions btw users and database objects
     * @param  string $event       	 Any of the next options CREATE UPDATE DELETE
     * @param  string $object_type 	 Name of the object being modified
     * @param  int $object_id  		 Primary key used to identify the object
     * @param  string $object      	 The object to be saved as json in the DB
     * @return boolean               Result of trying to save the object in the database
     */
    public static function record( $event, $object_type, $object_id, $object)
    {    	
    	$user = Auth::user();

        $log = new Log();
        $log->event = $event;
        $log->object_type = $object_type;
        $log->object_id = $object_id;
        $log->user_id = $user->id;
        $log->data = json_encode($object);
        return $log->save();
    }
}
