<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications;
use App\Role;
use App\ServiceProvider;
use App\User;
use Auth;

/**
 * User Controller.
 * Controller for the user functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Controller
 */
class UserController extends Controller
{
    /**
     * user contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['store']);
    }

    /**
     * Display a listing of users
     * @param Request $request request
     * @return view users information
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles('Administrator');
        $total_users = User::count();
        return view("user.index", compact('total_users'));
    }

    /**
     * Show the form for creating a new user
     * @param Request $request request
     * @return view user creation page
     */
    public function create(Request $request)
    {
        $request->user()->authorizeRoles('Administrator');

        $service_provider_obj = new ServiceProvider();
        $service_providers    = $service_provider_obj->getAllServiceproviders();

        if ($request->user()->sp_id != 0) {
            //If the user belongs to a service provider
            foreach ($service_providers as $service_provider) {
                if ($service_provider['ServiceProviderId'] == $request->user()->sp_id) {
                    $service_providers   = []; //Destroy array and override
                    $service_providers[] = $service_provider;
                }
            }
        }

        $role_obj = new Role();
        $roles       = $role_obj->all();

        return view("user.create", compact('service_providers', 'roles'));
    }

    /**
     * Store a newly or updated user in the data base
     * @return mixed user listing page with success/error message
     */
    public function store()
    {
        // Validate the form
        $this->validate(
            request(),
            [
                'name' => 'required',
                'email' => 'required|unique:users|email',
                'password' => 'required|confirmed|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-+]).{6,}$/',
            ]
        );

        //create and save the user
        $user = User::create([
                                'name'  => filter_var(request('name'), FILTER_SANITIZE_STRING),
                                'email' => filter_var(request('email'), FILTER_VALIDATE_EMAIL),
                                'password' => bcrypt(request('password'))
                            ]);

        //sign them in and add role
        if (request('sp_id') != 0) {
            $user->sp_id = request('sp_id'); //No service provider
        } else {
            $user->sp_id = 0; //No service provider
        }

        //Add status to User
        $user_request = request();
        $user->status   = isset($user_request['status']) ? 1 : 0;

        $user->save();
        //sign them in and Add role too
        if (isset($user_request['ro_id'])) {
            $user
                ->roles()
                ->attach(Role::where('id', request('ro_id'))->first());

            return redirect('/user');
        } else {
            //Add CLC Role as this is a functionality for them to register
            //Fact: VLA employees can auto-registrate via simple saml login
            $user
                ->roles()
                ->attach(Role::where('name', 'CLC')->first());

            $user->notifyRegistration($user);
            //Create the User
            return redirect('/registration')->with('success', 'Your request has been received, you will recive an email once your account is activated.');
        }
    }

    /**
     * List all user
     * @return array list of all user
     */
    public function list()
    {
        $users = User::with('roles')->get();
        foreach ($users as $key => $user) {
            $user['role'] = $user->roles()->first()->name;
            $users[$key]  = $user;
        }
        return [ 'data' => $users ];
    }

    /**
     * Get Users information in a data table format
     *
     * @param Request $request
     * @return Users    Users with paginator
     */
    public function listTable(Request $request)
    {
        $users = User::getUsersTable($request);

        return $users;
    }

    /**
     * Remove the specified user from data base.
     * @param  integer $uid user id
     * @param  Request $request request
     * @return mixed user listing page with success/error message
     */
    public function destroy(Request $request, $uid)
    {
        $request->user()->authorizeRoles('Administrator');
        $response = User::deleteUser($uid);

        return redirect('/user')->with($response['success'], $response['message']);
    }

    /**
     * Display a specific user
     * @param  integer $uid user id
     * @param  Request $request request
     * @return view single user information page
     */
    public function show(Request $request, $uid)
    {
        $request->user()->authorizeRoles('Administrator');

        $user = User::find($uid);

        $service_provider_obj   = new ServiceProvider();
        $service_providers      = $service_provider_obj->getAllServiceproviders();

        if ($request->user()->sp_id != 0) {
            //If the user belongs to a service provider
            foreach ($service_providers as $service_provider) {
                if ($service_provider['ServiceProviderId'] == $request->user()->sp_id) {
                    $service_providers = []; //Destroy array and override
                    $service_providers[] = $service_provider;
                }
            }
        }

        $role_obj = new Role();
        $roles    = $role_obj->all();

        return view("user.show", compact('user', 'service_providers', 'roles'));
    }

    /**
     * Update an user in the data base
     * @return mixed user listing page with success/error message
     */
    public function update(Request $request)
    {
        $request->user()->authorizeRoles('Administrator');

        $this->validate(
            request(),
            [
                                'name'  => 'required',
                                'email' => 'required|email',
                                //'password' => 'required|confirmed',
                                'id'    => 'required',
                                'sp_id' => 'required'
                            ]
        );

        $response = User::updateUser(request());

        return redirect('/user')->with($response['success'], $response['message']);
    }
    /**
     * Clear Notifications
     *
     * @return void
     */
    public function clearNotifications()
    {
        $notifications = new Notifications();
        $notifications->clearNotifications();
    }
    /**
     * Return view to change password
     *
     * @return void
     */
    public function changePassword()
    {
        return view("user.change_password");
    }
    /**
     * Change User Password
     *
     * @return void
     */
    public function changeUserPassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'old_password' => 'required',
                'password' => 'required|confirmed|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-+]).{6,}$/',
            ]
        );

        $response = User::updateUserPassword($request);
        if ($response['success']=='error') {
            return redirect()->back()->withInput($request->input())
                                    ->withErrors($response['message'], $this->errorBag());
            ;
        }
        return redirect('/')->with($response['success'], $response['message']);
    }
}
