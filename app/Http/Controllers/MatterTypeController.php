<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MatterType;
use Auth;

/**
 * Matter Type Controller.
 * Controller for the legal matter type functionalities
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  Controller
 */
class MatterTypeController extends Controller
{
    /**
     * Matter Type contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of legal matter type
     * @return view legal matter type information
     */
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("matter_type.index");
    }
    /**
     * Display a specific legal matter type
     * @param  int  $mt_id    legal matter type Id
     * @return view single legal matter type information page
     */
    public function show($mt_id)
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("matter_type.show");
    }
    /**
     * Show the form for creating a new legal matter type
     * @return view legal matter type creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("matter_type.create");
    }
    /**
     * Store a newly or updated legal matter type in the data base
     * @return mixed  legal matter type listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles('Administrator');
        $matter_type_params = [ 'title'   => request('title')];

        $matter_type = new MatterType();
        $response = $matter_type->saveMatterType($matter_type_params);

        return redirect('/matter_type')->with($response['success'], $response['message']);
    }
    /**
     * Remove the specified legal matter type from data base.
     * @param  int $mt_id legal matter type Id
     * @return mixed legal matter type listing page with success/error message
     */
    public function destroy($mt_id)
    {
        Auth::user()->authorizeRoles('Administrator');
        $matter_type = new MatterType();
        $response = $matter_type->deleteMatter($mt_id);

        return redirect('/matter_type')->with($response['success'], $response['message']);
    }
    /**
     * List all legal matter types
     * @return array list of all legal matter types
     */
    public function list()
    {
        $matter_type = new MatterType();

        $result = $matter_type->getAllMatterTypes();

        return ['data' => $result];
    }

}
