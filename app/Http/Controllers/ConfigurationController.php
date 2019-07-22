<?php

namespace App\Http\Controllers;

use App\Configuration;
use Illuminate\Http\Request;
use Auth;
/**
 * Configuration Controller.
 * Controller to manage standar functionalities in Orbit
 * @author Christian Arevalo
 * @version 1.0.1
 * @see  Controller
 */
class ConfigurationController extends Controller
{
    /**
     * Service contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of Configurations.
     */
    public function index(Request $request)
    {
        Auth::user()->authorizeRoles('Administrator');


        return view("configuration.index");
    }

    /**
     * Show the form for creating a new Configuration.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("configuration.create");
    }

    /**
     * Store a newly created Configuration in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Auth::user()->authorizeRoles('Administrator');

        $this->getData($request); // Validate errors

        $configurations = new Configuration();
        $response = $configurations->save($request->all());

        return redirect('/configuration')->with($response['success'], $response['message']);

    }

    /**
     * Display the specified Configuration.
     *
     * @param  \App\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    //public function show(Configuration $configuration)
    public function show($configuration)
    {
        Auth::user()->authorizeRoles('Administrator');

        $configurations = new Configuration();
        $response = $configurations->getConfigurationByKey($configuration);
        if($response['success'] == 'success') {
            $current_config = $response['message'];
            return view('configuration.show', compact('current_config'));
        } else {
            return redirect('configuration')->with($response['success'], $response['message']);
        }
    }

    /**
     * Remove the specified Configuration from storage.
     *
     * @param  \App\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function destroy($configuration)
    {
        Auth::user()->authorizeRoles('Administrator');

        $configurations  = new Configuration();
        $response = $configurations->deleteConfiguration($configuration);

        return redirect('configuration')->with($response['success'], $response['message']);

    }

    /**
     * List all configurations in a data table format
     *
     * @return array
     */
    public function listTable()
    {
        Auth::user()->authorizeRoles('Administrator');

        $configurations = new Configuration();
        $response = $configurations->getAllConfigurations();
        if($response['success'] == 'success'){
            return [ 'data' => $response['data'] ];
        } else {
            return [ 'error' => $response['message'] ];
        }
    }

    /**
     * Get the configuration's data from the request or return with errors.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'Key'   => 'required',
            'Name'  => 'required',
            'Value' => 'required'
        ];

        $this->validate($request, $rules);
    }
}
