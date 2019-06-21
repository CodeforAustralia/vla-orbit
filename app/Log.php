<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Log Model.
 *
 * @author Christian Arevalo
 * @version 1.2.1
 * @see Model
 */
class Log extends Model
{

    /**
     * Cast fields to the needed data type
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array'
    ];

    /**
     * Record interactions btw users and database objects
     * @param  string $event       	 Any of the next options CREATE, UPDATE, DELETE
     * @param  string $object_type 	 Name of the object being modified
     * @param  integer $object_id  	 Primary key used to identify the object
     * @param  object $object      	 The object to be saved as json in the DB
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
        $log->data = $object;
        return $log->save();
    }

    /**
     * Query log information by a specified data condition
     *
     * @param string $object_type
     * @param int    $object_id
     * @param array  $data_condition    The syntax should be [field, operator, value] ie. ['data->general_settings->Notes', '!=', '']
     * @return array
     */
    public static function getLogByDataCondition($object_type, $object_id, $data_condition)
    {
        $log = new Log();
        $field = $data_condition[0];
        $operator = $data_condition[1];
        $value = $data_condition[2];
        return $log->where('object_type', $object_type)
                        ->where('object_id', $object_id)
                        ->where($field, $operator, $value)
                        ->orderBy('id', 'desc')
                        ->get()
                        ->toArray();
    }

    /**
     * Get service notifications.
     *
     * @param [type] $service_id
     * @return void
     */
    public static function getServiceLastNotification($service_id)
    {
        $log = new Log();
        return $log->where('object_type', 'service_notification')
                    ->where('object_id', $service_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

    }
}
