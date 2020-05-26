<?php

namespace App\Http\Controllers;

use App\ServiceLog;
use Illuminate\Http\Request;
use Auth;

/**
 * ServiceLog Controller.
 * Controller to manage standard functionalities in Orbit
 * @author Christian Arevalo
 * @version 1.0.1
 * @see  Controller
 */
class ServiceLogController extends Controller
{
    /**
     * Service constructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getBySvId($sv_id)
    {
        $service_log = new ServiceLog();
        return $service_log->getGeneralSettings($sv_id);
    }
}
