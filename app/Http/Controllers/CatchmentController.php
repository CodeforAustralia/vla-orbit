<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catchment;
use Auth;

/**
 * Catchment Controller.
 * Controller for the catchment functionality
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  Controller
 */
class CatchmentController extends Controller
{
    /**
     * Catchment Constructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of Catchment
     * @return view Catchment information
     */
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("catchment.index");
    }
    /**
     * Display a specific Catchment
     * @return view single Catchment information page
     */
    public function show()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("catchment.show");
    }

    public function store()
    {

    }
    /**
     * Show the form for creating a new Catchment
     * @return view Catchment creation page
     */
    public function create()
    {

        Auth::user()->authorizeRoles('Administrator');

        return view("catchment.create");
    }
    /**
     * List all Catchment
     * @return array list of all catchment
     */
    public function list()
    {
        $catchment = new Catchment();
        $result  = $catchment->getAllCatchments();
        return array( 'data' => $result );
    }
    /**
     * List all Catchment with special format
     * @return array list of all catchment formated
     */
    public function listFormated()
    {
        $catchment = new Catchment();
        $result = $catchment->getAllCatchmentsFormated();
        return $result;
    }
    /**
     * List LGC Catchment
     * @return array filtered catchment by lgcs
     */
    public function listLgcs()
    {
        $catchment = new Catchment();
        $result = $catchment->getDistinctLGC();
        return $result;
    }
    /**
     * List suburbs Catchemnt
     * @return array filtered catchment by suburb
     */
    public function listSuburbs()
    {
        $catchment = new Catchment();
        $result = $catchment->getDistinctSuburb();
        return $result;
    }
}