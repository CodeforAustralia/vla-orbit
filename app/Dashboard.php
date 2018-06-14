<?php

namespace App;

use App\Notifications;

/**
 * Dashboard model to display messages on homepage
 * @author Christian Arevalo
 * @version 1.2.0
 */
class Dashboard extends Model
{
    /**
     * Store Dashboard information
     *
     * @param array $params Dashboard information
     * @return void
     */
    public static function store($params)
    {
        $count = Dashboard::count();

    	if ( isset( $params['id'] ) && $params['id'] !== '' ) {

    		$dashboard = Dashboard::findOrFail($params['id']);
	    	$dashboard->title = $params['title'];
            $dashboard->body = $params['body'];
	    	$dashboard->position = $count;
	    	$dashboard->save();
    	} else {
    		$new_event = Dashboard::create( [
                                    'title'    => $params['title'],
                                    'body'     => $params['body'],
                                    'position' => $count
                             ] );

            $args['object_id'] = $new_event->id;
            $args['object_type'] = 'dashboard';
            $args['message'] = $params['title'];
            $args['url'] = '/';

            $notification = new Notifications();
            $notification->recordNotification($args);
    	}
    	return;
    }

    /**
     * Delete dashboard item by ID
     *
     * @param integer $id Dashboard item iD
     * @return void
     */
    public static function destroy( $id )
    {
        $dashboard = Dashboard::findOrFail($id);
        $dashboard->delete();
        return;
    }

    /**
     * Update position on the wall for an individual Dasboard Item
     *
     * @param integer $positions array of Dasboard items in their final positions
     * @return void
     */
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
