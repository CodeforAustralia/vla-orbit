<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceProvider;
use App\User;
use Carbon\Carbon;
use Auth;
use Validator;

/**
 * Service Provider Controller.
 * Controller for the service provider functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Controller
 */
class ServiceProvidersController extends Controller
{
    /**
     * Service provider contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of service provider
     * @return view service provider information
     */
    public function index()
    {
        return view("service_provider.index");
    }

    /**
     * Display a specific service provider
     * @param int $sp_id service provider id
     * @return view single service provider information page
     */
    public function show($sp_id)
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp','AdminSpClc'] );

        $is_admin = (new \App\Repositories\RolesCheck)->is_admin();

        if ( Auth::user()->sp_id == $sp_id || $is_admin ) {
            $service_provider_obj   = new ServiceProvider();
            $result                 = $service_provider_obj->getServiceProviderByID($sp_id);

            if ( isset( $result['data'] ) ) {
                $current_sp = json_decode( $result['data'] )[0];
                return view( "service_provider.show", compact( 'current_sp' ) );
            } else {
                return redirect('/service_provider')->with( $response['success'], $response['message'] );
            }
        } else {
            return redirect('/service_provider')->with( 'error', 'Not Authorized' );
        }
    }

    /**
     * Store a newly or updated service provider in the data base
     * @return mixed service provider listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp'] );
        $rules = [
            'contact_phone' => 'required|numeric|max:9999999999'
        ];
        $validator = Validator::make( request()->all(), $rules );

        if ( $validator->fails() ) {

            return redirect()->back()->with('error', ' Phone should be numeric and may not be greater than 10 numbers ')->withInput();
        }
        // Validate the form


        $sp_params = [
                        'ServiceProviderId'     => request('sp_id'),
                        'ServiceProviderName'   => filter_var(request('name'), FILTER_SANITIZE_STRING),
                        'ContactEmail'          => filter_var(request('contact_email'), FILTER_VALIDATE_EMAIL),
                        'ContactName'           => filter_var(request('contact_name'), FILTER_SANITIZE_STRING),
                        'ContactPhone'          => filter_var(request('contact_phone'), FILTER_SANITIZE_STRING),
                        'ServiceProviderAbout'  => filter_var(request('about'), FILTER_SANITIZE_STRING),
                        'ServiceProviderLogo'   => filter_var(request('logo'), FILTER_SANITIZE_STRING),
                        'ServiceProviderURL'    => filter_var(request('url'), FILTER_SANITIZE_URL),
                        'ServiceProviderAddress' => filter_var(request('address'), FILTER_SANITIZE_STRING),
                        'ServiceProviderTypeId' => request('spt_id')
                      ];

        $service_provider = new ServiceProvider();
        $response = $service_provider->saveServiceProvider( $sp_params );

        return redirect('/service_provider')->with( $response['success'], $response['message'] );
    }

   /**
     * Show the form for creating a new service provider
     * @return view service provider creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("service_provider.create");
    }

    /**
     * Remove the specified service provider from data base.
     * @param  int $sp_id service provider id
     * @return mixed service provider listing page with success/error message
     */
    public function destroy($sp_id)
    {
        Auth::user()->authorizeRoles('Administrator');

        $service_provider = new ServiceProvider();
        $response = $service_provider->deleteServiceProvider($sp_id);

        return redirect('/service_provider')->with( $response['success'], $response['message'] );
    }

    /**
     * List all service provider
     * @return array list of all service provider
     */
    public function list()
    {
        $service_provider = new ServiceProvider();
        $result = $service_provider->getAllServiceProviders();
        return [ 'data' => $result ];
    }

    /**
     * List all service provider
     * @return array list of all service provider
     */
    public function listFormated()
    {
        $service_provider = new ServiceProvider();
        $result = $service_provider->getAllServiceProvidersFormated( request('scope') );
        return $result;
    }
    /**
     * Set Service provicer and name for users with no service provider and role VLA
     */
    public function setServiceProvider()
    {

        $request = request()->all();

        $user_obj = new User();
        $user_obj->id = Auth::user()->id;
        $user_obj->email = Auth::user()->email;
        $user_obj->sp_id=$request['sp'];
        $user_obj->ro_id=Auth::user()->roles()->first()->id;
        $user_obj->name=filter_var($request['name'], FILTER_SANITIZE_STRING);
        if ( empty($request['name']) ) {
            $user_obj->name=Auth::user()->name;
        }

        $response = User::updateUser( $user_obj );

        return redirect('/')->with($response['success'], $response['message']);
    }
}
