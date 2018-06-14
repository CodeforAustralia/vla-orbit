<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User model for the user functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Authenticatable
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =[
                            'name', 'email', 'password',
                         ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
                            'password',
                            'remember_token',
                        ];

    /**
     * Get users post
     * @return Object relation between user and post
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    /**
     * Save post
     * @param  Post   $post post
     * @return
     */
    public function publish( Post $post )
    {
        $this->posts()->save( $post );
    }
    /**
     * Get user roles
     * @return Object user roles
     */
    public function roles()
    {
        return $this
               ->belongsToMany( 'App\Role' )
               ->withTimestamps();
    }
    /**
     * Check if the user has a specific role
     * @param  Object  $roles
     * @return boolean true if the user has the role abort otherwise
     */
    public function authorizeRoles($roles)
    {
        if ( $this->hasAnyRole( $roles ) ) {
            return true;
        }
        abort( 401, 'This action is unauthorized.');
    }
    /**
     * Check if the user has a specific role
     * @param  Object  $roles
     * @return boolean        true if the user has the role false otherwise
     */
    public function hasAnyRole( $roles )
    {
        if ( is_array( $roles ) ) {
            foreach ( $roles as $role ) {
                if ( $this->hasRole( $role ) ) {
                    return true;
                }
            }
        } elseif ( $this->hasRole($roles) ) {
            return true;
        }
        return false;
    }
    /**
     * Check if the user has a specific role
     * @param  Object  $roles
     * @return boolean        true if the user has the role false otherwise
     */
    public function hasRole($role)
    {
        if ( $this->roles()->where('name', $role)->first() ) {
            return true;
        }
        return false;
    }
    /**
     * Delete user
     * @param  int    $uid user id
     * @return array       sucess status and message
     */
    public static function deleteUser( $uid )
    {
        $user = User::find( $uid );

        if ( $user ) {
            $user->delete();
            return [ 'success' => 'success' , 'message' => 'User has been deleted.' ];
        }
        return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
    }
    /**
     * Update user
     * @param  Object $user_info user details
     * @return array             sucess status and message
     */
    public static function updateUser( $user_info )
    {
        $user = User::find( $user_info->id );

        //create and save the user

        $user->name     = $user_info->name;
        $user->email    = $user_info->email;
        $user->sp_id    = $user_info->sp_id;

        //sign them in and Add role too
        $user
           ->roles()
           ->sync(Role::where( 'id',  $user_info->ro_id )->first() );

        $user->save();

        return [ 'success' => 'success' , 'message' => 'User has been updated.' ];

    }

    /**
     * User notifications
     *
     * @return Notifications Collection of notifications for the current User
     */
    public function notifications()
    {
        return $this->hasMany(Notifications::class)->where('seen', 0);
    }

    /**
     * User notifications
     *
     * @return array Array with number of notifications per user and all the notifications to be displayed
     */
    public function userNotifications()
    {
        $notifications = self::notifications()->get()->all();
        return ['count' => sizeof($notifications) , 'all' => $notifications] ;
    }
}
