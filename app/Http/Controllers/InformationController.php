<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupEmail;
use Auth;

/**
 * Information Controller.
 * Controller for the information page
 *
 * @author Sebastian Currea
 * @version 1.1.0
 * @see  Controller
 */
class InformationController extends Controller
{
    /**
     * No reply email contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Send and contact email to request access
     *
     */
    public function contact()
    {
        // Validate the form
        $this->validate(
                            request(),
                            [
                                'name' => 'required',
                                'email' => 'required|email',
                                'message' => 'required'
                            ]
                        );
        $app_name = strtoupper(config('app.name'));
        $args['Message'] = 'Thanks for showing interest in '. $app_name .'. Please fill in your details below and an '. $app_name .' team member will get in touch shortly.<br><br>

                            Name:' . request('name') .'<br>
                            Email address:' . request('email') .'<br>
                            Message:' . request('message') ;

        Mail::to(config('app.team_email'))->send( new SignupEmail( $args ) );
    }
}
