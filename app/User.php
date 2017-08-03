<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function posts() {
        return $this->hasMany(Post::class);
    }
    
    public function publish(Post $post){
        $this->posts()->save($post);
    }

    public function roles()
    {
      return $this
        ->belongsToMany('App\Role')
        ->withTimestamps();
    }

    public function authorizeRoles($roles)
    {
      if ($this->hasAnyRole($roles)) {
        return true;
      }
      abort(401, 'This action is unauthorized.');
    }

    public function hasAnyRole($roles)
    {
      if (is_array($roles)) {
        foreach ($roles as $role) {
          if ($this->hasRole($role)) {
            return true;
          }
        }
      } else {
        if ($this->hasRole($roles)) {
          return true;
        }
      }
      return false;
    }
    
    public function hasRole($role)
    {
      if ($this->roles()->where('name', $role)->first()) {
        return true;
      }
      return false;
    }

    public static function deleteUser($uid)
    {
      $user = User::find($uid);
      
      if( $user )
      {
        $user->delete();
        return array( 'success' => 'success' , 'message' => 'User has been deleted.' );
      }
      return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
    }

    public static function updateUser( $user_info )
    {
      $user = User::find($user_info->id);

        //create and save the user
        
        $user->name     = $user_info->name;
        $user->email    = $user_info->email;  
        $user->sp_id    = $user_info->sp_id;
        
        //sign them in and Add role too
        $user
           ->roles()
           ->sync(Role::where('id',  $user_info->ro_id)->first());

        $user->save();
        
        return array( 'success' => 'success' , 'message' => 'User has been updated.' );
      
    }
}
