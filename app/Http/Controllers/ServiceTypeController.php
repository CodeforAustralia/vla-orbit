<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceType;
use Auth;

/**
 * Service Type Controller.
 * Controller for the service type functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Controller
 */
class ServiceTypeController extends Controller
{
    /**
     * Service type contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of service type
     * @return view service type information
     */
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_type.index");
    }

    /**
     * Display a specific service type
     * @return view single service type information page
     */
    public function show()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_type.show");
    }

   /**
     * Show the form for creating a new service type
     * @return view service type creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_type.create");
    }

    /**
     * Store a newly or updated service type in the data base
     * @return mixed service type listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles('Administrator');
        $service_type_params =  [
                                    'title'         => request('title'),
                                    'description'   => request('description'),
                                ];

        $service_type = new ServiceType();
        $response = $service_type->saveServiceType($service_type_params);

        return redirect('/service_type')->with($response['success'], $response['message']);
    }

    /**
     * Remove the specified service type from data base.
     * @param  int $st_id service type id
     * @return mixed service type listing page with success/error message
     */
    public function destroy($st_id)
    {
        Auth::user()->authorizeRoles('Administrator');
        $service_type = new ServiceType();
        $response = $service_type->deleteServiceType($st_id);

        return redirect('/service_type')->with($response['success'], $response['message']);
    }

    /**
     * List all service type
     * @return array list of all service type
     */
    public function list()
    {
        $service_type = new ServiceType();
        $result = $service_type->getAllServiceTypes();

        return ['data' => $result];
    }
}
