<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class PanelLawyers extends Model
{

    protected $table ='panel_lawyers';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'firm_name', 
        'address', 
        'lat', 
        'lng', 
        'phone', 
        'created_at', 
        'updated_at', 
    ];
    /**
     * Delete Panel Lawyer
     * @param  Integer $pid panel lawyer id
     * @return Array      Success message
     */
    public static function deletePanelLawyer($pid)
    {
      $panelLawyer = PanelLawyers::find($pid);
      
      if( $pid )
      {
        $panelLawyer->delete();
        return array( 'success' => 'success' , 'message' => 'Panel Lawyer has been deleted.' );
      }
      return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
    }

    /**
     * Update Panel Lawyer
     * @param  Request $panel_lawyer_info Panel Lawyer info
     * @return Array                    Success Message
     */
    public static function updatePanelLawyer( $panel_lawyer_info )
    {
 
      $panel_lawyers = PanelLawyers::find($panel_lawyer_info->id);

       
        $panel_lawyers->firm_name       = $panel_lawyer_info->firm_name;
        $panel_lawyers->address         = $panel_lawyer_info->address;  
        $panel_lawyers->lat             = $panel_lawyer_info->lat;
        $panel_lawyers->lng             = $panel_lawyer_info->lng;
        $panel_lawyers->phone           = $panel_lawyer_info->phone;
        
        $panel_lawyers->save();
        
        return array( 'success' => 'success' , 'message' => 'PanelLawyer has been updated.' );
      
    }

   public static function createPanelLawyer( $panel_lawyer_info )
   {
        PanelLawyers::create([
        'firm_name'     => $panel_lawyer_info->firm_name,
        'address'       => $panel_lawyer_info->address,
        'lat'           => $panel_lawyer_info->lat,
        'lng'           => $panel_lawyer_info->lng,
        'phone'         => $panel_lawyer_info->phone                
    ]);

    return array( 'success' => 'success' , 'message' => 'PanelLawyer has been created.' );

   }
 


/*
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
    }*/
}
