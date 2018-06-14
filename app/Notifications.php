<?php

namespace App;

use App\User;
use Auth;

class Notifications extends Model
{
    /**
     * Record notifications
     *
     * @param array $args Array of [user_id, object_id, object_type, message, url, seen]
     * @return void
     */

    public static function recordNotification($args)
    {
        $notification = new Notifications();
        $user = new User();

        $all_users = $user->get()->all();

        foreach ($all_users as $current_user) {
            $notification->create([
                                    'user_id' => $current_user->id,
                                    'object_id' => $args['object_id'],
                                    'object_type' => $args['object_type'],
                                    'message' => $args['message'],
                                    'url' => $args['url'],
                                    'seen' => false,
                                ]);
        }
    }

    /**
     * Clear current user notifications
     *
     * @return void
     */
    public static function clearNotifications()
    {
        $user = Auth::user();
        $notifications = $user->userNotifications();

        foreach ($notifications['all'] as $notification) {
            $notification['seen'] = true;
            $notification->save();
        }
    }
}
