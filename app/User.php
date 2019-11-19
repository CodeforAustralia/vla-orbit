<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupEmail;
use Carbon\Carbon;

/**
 * User model for the user functionalities
 * @author Christian Arevalo
 * @version 1.2.0
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
                            'name',
                            'email',
                            'password',
                            'last_login_at'
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
     * Get user roles
     * @return Object user roles
     */
    public function roles()
    {
        return $this
                ->belongsToMany('App\Role')
                ->withTimestamps();
    }

    /**
     * Return the role for current user
     * @return String user role
     */
    public function getRoleAttribute()
    {
        return Auth::user()->roles()->first()->name;
    }

    /**
     * Check if current user is an administrator
     * @return boolean true if is administrator false otherwise.
     */
    public function isAdmin()
    {
        $role = Auth::user()->role;
        return ($role == 'Administrator');
    }

    /**
     * Check if the user has a specific role
     * @param  Object  $roles
     * @return boolean true if the user has the role abort otherwise
     */
    public function authorizeRoles($roles)
    {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'This action is unauthorized.');
    }
    /**
     * Check if the user has a specific role
     * @param  Object  $roles
     * @return boolean        true if the user has the role false otherwise
     */
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } elseif ($this->hasRole($roles)) {
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
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }
    /**
     * Delete user
     * @param  int    $uid user id
     * @return array       sucess status and message
     */
    public static function deleteUser($uid)
    {
        $user = User::find($uid);

        if ($user) {
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
    public static function updateUser($user_info)
    {
        $user = User::find($user_info->id);

        // Check if an account has been activated and should be notified
        $notify = false;
        if (isset($user_info->status) && $user->status == 0) {
            $notify = true;
        }

        //create and save the user
        $user->name     = $user_info->name;
        $user->email    = $user_info->email;
        $user->sp_id    = $user_info->sp_id;
        $user->status   = isset($user_info->status) ? 1 : 0;

        //sign them in and Add role too
        $user
            ->roles()
            ->sync(Role::where('id', $user_info->ro_id)->first());

        $user->save();

        //Notify User after
        if ($notify) {
            $user->notifyToUserAccountActivation($user_info->email);
        }

        return [ 'success' => 'success' , 'message' => 'User has been updated.' ];
    }

    /**
    * Update user
    * @param  Object $user_info user details
    * @return array             sucess status and message
    */
    public static function updateUserPassword($user_info)
    {
        $user = User::find($user_info->id);

        //create and save the user
        $hasher = app('hash');
        if ($hasher->check($user_info->old_password, $user->password)) {
            $user->password = bcrypt($user_info->password);
            $user->save();
            return [ 'success' => 'success' , 'message' => 'User password has been changed.' ];
        }
        return [ 'success' => 'error' , 'message' => 'Old password does not match.' ];
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

    /**
     * Get users information to be displayed in a data tabe format
     *
     * @param Request $request
     * @return User
     */
    public static function getUsersTable($request)
    {
        $search_value = '%' . $request->search . '%';
        $query = User::prepareResourcesQuery($search_value);
        if (isset($request->column) && !is_null($request->column)) {
            $column = $request->column;
            if ($request->column == 'name') {
                $column = 'users.name';
            }
            if ($request->column == 'id') {
                $column = 'users.id';
            }
            $query->orderBy($column, $request->order);
        }
        $data = $query->paginate($request->per_page);
        return $data;
    }
    /**
     * Create query limiting users of each service provider and fields
     *
     * @param String $search_value
     * @return DBQuery
     */
    public static function prepareResourcesQuery($search_value)
    {
        $query = DB::table('users')
                    ->join('role_user', function ($join) {
                        $join->on('role_user.user_id', '=', 'users.id');
                    })
                    ->join('roles', function ($join) {
                        $join->on('role_user.role_id', '=', 'roles.id');
                    })
                    ->select(
                        User::getUsersFieldsToShow()
                    )
                    ->orWhere('roles.name', 'LIKE', '%'.$search_value.'%')
                    ->orWhere('users.name', 'LIKE', '%'.$search_value.'%')
                    ->orWhere('users.email', 'LIKE', '%'.$search_value.'%')
                    ->orWhere('users.sp_id', 'LIKE', '%'.$search_value.'%');
        return $query;
    }

    /**
     * Get fields to be displayed in tables
     *
     * @return array
     */
    public static function getUsersFieldsToShow()
    {
        $fields = [
            'users.id',
            'users.name',
            'users.email',
            'users.sp_id',
            'roles.name as role',
        ];
        return $fields;
    }

    /**
     * Check if the current user belongs to a service provider of type CLC
     *
     * @return boolean
     */
    public function isClcUser()
    {
        return in_array(\App\Http\helpers::getRole(), ['CLC', 'AdminSpClc']) ;
    }

    /**
     * Notify base team a User registration
     *
     * @param User $user
     * @return void
     */
    public function notifyRegistration($user)
    {
        $app_name = strtoupper(config('app.name'));

        $args['Message'] = 'The user '. $user->name .' has registered in '. $app_name .'.
                            <br><br>

                            Please go to the Users section and validate its details before activate it.
                            <br><br>

                            Regards,<br><br>

                            LHO Team.';

        $args['subject'] = 'Account Request';

        Mail::to(config('app.team_email'))->send(new SignupEmail($args));
    }

    /**
     * Notify base team a User registration
     *
     * @param User $user
     * @return void
     */
    public function notifyToUserAccountActivation($email)
    {
        $app_name = strtoupper(config('app.name'));
        $app_url = strtoupper(config('app.url'));

        $args['Message'] = 'Hi, your account in '. $app_name .' is now active.
                            <br><br>

                            If you want to start using the tool please follow this <a href="' . $app_url .'/login">link</a>.
                            <br><br>

                            Regards,<br><br>

                            LHO Team.';

        $args['subject'] = 'Account activated - ' . $app_name;

        Mail::to($email)->send(new SignupEmail($args));
    }

    /**
     * Store last login of Users
     *
     * @return void
     */
    public function setLoginDate()
    {
        $this->update([
            'last_login_at' => Carbon::now()->toDateTimeString()
        ]);
    }
}
