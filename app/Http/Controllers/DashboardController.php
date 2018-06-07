<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Dashboard;

/**
 * Dashboard Controller.
 * Controller for the Dashboard functionalities
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see Controller
 */
class DashboardController extends Controller
{
    /**
     * Dashboard constructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $request->user()->authorizeRoles('Administrator');
        $dashboards = Dashboard::all()->sortBy('position');

        return view('dashboard.index', compact('dashboards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        $request->user()->authorizeRoles('Administrator');

        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $request->user()->authorizeRoles('Administrator');
        // post to store
        // save it to the DB

        $this->validate( request(),[
            'title' => 'required',
            'body' => 'required'
        ]);
        Dashboard::store( request()->all() );

        return redirect('/dashboard')->with( 'success', 'Saved' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Request $request, $id)
    {
        $request->user()->authorizeRoles('Administrator');
        $dashboard = Dashboard::find($id);

        return view('dashboard.show', compact( 'dashboard' ));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id)
    {
        $request->user()->authorizeRoles('Administrator');
        Dashboard::destroy( $id );

        return redirect('/dashboard')->with( 'success', 'Message deleted' );
    }

    public function updatePositions( Request $request )
    {
        $request->user()->authorizeRoles('Administrator');
        Dashboard::updatePositions( $request['positions'] );

        return 'done';
    }
}
