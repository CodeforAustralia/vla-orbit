<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NoReplyEmail;

class NoReplyEmailController extends Controller
{    
    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function listAllTemplates()
    {
        $nre_obj = new NoReplyEmail(); 
        //return $nre_obj->getAllTemplates();
        
        return $nre_obj->sendEmail();
    }

    public function listAllTemplatesBySection( $section )
    {
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getAllTemplatesBySection( $section );
    }

    public function listAllMailBoxes()
    {
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getAllMailBoxes();
    }
}
