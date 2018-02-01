<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    public static function store($params)
    {
        $count = Dashboard::count();
    	if( isset( $params['id'] ) && $params['id'] !== '' )
    	{
    		$dashboard = Dashboard::findOrFail($params['id']);
	    	$dashboard->title = $params['title'];
            $dashboard->body = $params['body'];
	    	$dashboard->position = $count;
	    	$dashboard->save();
    	}
    	else
    	{
    		Dashboard::create( [ 
                                    'title'    => $params['title'], 
                                    'body'     => $params['body'],
                                    'position' => $count
                             ] );
    	}
    	return;
    }

    public static function destroy( $id )
    {
        $dashboard = Dashboard::findOrFail($id);
        $dashboard->delete();
        return;
    }

    public static function updatePositions( $positions )
    {
        foreach ($positions as $position => $id) {            
            $dashboard = Dashboard::findOrFail($id);
            $dashboard->position = $position;
            $dashboard->save();
        }
    	return;
    }
}
