<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Dashboard;

class DashboardController extends Controller
{
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
        // page to create 
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
        //dd($dashboard);
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
