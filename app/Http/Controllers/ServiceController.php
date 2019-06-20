<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Service;
use App\ServiceBooking;
use App\ServiceBookingQuestions;
use App\ServiceType;
use App\ServiceLevel;
use App\ServiceProvider;
use App\Matter;
use App\MatterService;
use App\Catchment;
use App\Vulnerability;
use App\EReferral;
use App\Question;
use App\Mail\RequestEmail;
use App\MatterServiceAnswer;
use Auth;

/**
 * Service Controller.
 * Controller for the service functionalities
 * @author Christian Arevalo
 * @version 1.3.0
 * @see  Controller
 */
class ServiceController extends Controller
{
    /**
     * Service contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of service
     * @return view service information
     */
    public function index()
    {
        return view("service.index");
    }

    /**
     * Display a specific service
     * @param  integer $sv_id service id
     * @return view single service information page
     */
    public function show( $sv_id )
    {
        $user = Auth::user();

        $service_type_obj   = new ServiceType();
        $service_types      = $service_type_obj->getAllServiceTypes();

        $service_level_obj  = new ServiceLevel();
        $service_levels     = $service_level_obj->getAllServiceLevels();

        $service_provider_obj   = new ServiceProvider();
        $service_providers      = $service_provider_obj->getAllServiceproviders();

        $matter_type_obj    = new Matter();
        $matters            = $matter_type_obj->getAllMatters();

        $matter_service_obj     = new MatterService();
        $matter_services_list   = $matter_service_obj->getMatterServiceBySvID($sv_id);
        $matter_services = [];

        $service_booking_obj = new ServiceBooking();
        $service_booking = $service_booking_obj->getServiceBookingByServiceId($sv_id);

        foreach ($matter_services_list as $matter_service) {
            $matter_services[] = $matter_service->MatterId;
        }

        $service = new Service();
        $result  = $service->getServiceByID($sv_id);

        $vulnerability_obj = new Vulnerability();
        $vulnertability_questions = $vulnerability_obj->getAllVulnerabilityQuestions();

        $service_booking_questions_obj = new ServiceBookingQuestions();
        $service_booking_questions = $service_booking_questions_obj->getAllServiceBookingQuestions();

        if (isset( $result['data'] ) ) {
            $current_service = json_decode( $result['data'] )[0];
            $current_vulnerabilities = array_column($current_service->ServiceVulAnswers, 'QuestionId');

            $catchment = new Catchment();
            $catchments = $catchment->sortCatchments( $current_service->ServiceCatchments );

            $referral_conditions = [];
            $booking_conditions = [];
            $e_referral_conditions = [];
            foreach ( $current_service->ServiceActions as $action ) {
                if ( $action->Action === 'REFER' ) {
                    $referral_conditions[] = $action->ServiceProviderId;
                }
                if ( $action->Action === 'BOOK' ) {
                    $booking_conditions[] = $action->ServiceProviderId;
                }
                if ( $action->Action === 'E_REFER' ) {
                    $e_referral_conditions[] = $action->ServiceProviderId;
                }
            }

            $e_referral_forms = array_column($current_service->ReferralFormServices, 'ReferralFormID'); // All the referral forms associated to this service

            $service_notes_log = $service->getServiceNotesLogs($sv_id); //Get log of notes made in this service

            if ( $user->sp_id == $current_service->ServiceProviderId
                || $user->roles()->first()->name == 'Administrator' ) {
                //Same service provider or admin
                return view(
                                "service.show",
                                compact( 'current_service', 'service_types', 'service_levels', 'service_providers', 'matters',
                                        'matter_services' , 'catchments', 'vulnertability_questions', 'current_vulnerabilities',
                                        'referral_conditions', 'booking_conditions', 'e_referral_conditions', 'e_referral_forms',
                                        'service_booking', 'service_booking_questions', 'service_notes_log')
                            );
            } else {
                abort(401, 'This action is unauthorized.');
            }

        } else {
            return redirect('/service')->with( $result['success'], $result['message'] );
        }
    }

    /**
     * Store a newly or updated service in the data base
     * @return mixed service listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp', 'AdminSpClc'] );

        $sv_params = [
                        'ServiceId'   	=> request('sv_id'),
                        'ServiceName'   => filter_var(request('name'), FILTER_SANITIZE_STRING),
                        'Phone'         => filter_var(request('phone'), FILTER_SANITIZE_STRING),
                        'Email'         => filter_var(request('email'), FILTER_SANITIZE_EMAIL),
                        'Description'   => request('description'),
                        'Notes'         => isset($service['Notes']) ? $service['Notes'] : '',
                        'Location'      => filter_var(request('location'), FILTER_SANITIZE_STRING),
                        'URL'           => filter_var(request('URL'), FILTER_SANITIZE_URL),
                        'ServiceProviderId' => request('service_provider_id'),
                        'Wait'           => filter_var(request('wait'), FILTER_SANITIZE_STRING),
                        'ServiceLevelId' => request('service_level_id'),
                        'ServiceTypeId'  => request('service_type_id'),
                        'OpenningHrs'    => filter_var(request('OpenningHrs'), FILTER_SANITIZE_STRING),
                        'Status'         => ( request('Status') == 'on' ? 1 : 0 ),
                        'Specialist'     => false,
                    ];

        $service = new Service();
        $response = $service->saveService( $sv_params, request()->all() );

        return redirect('/service')->with( $response['success'], $response['message'] );
    }

    /**
     * Update service intake options of an existing service
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse service listing page with success/error message
     */
    public function storeGeneralSettings(Request $request)
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp', 'AdminSpClc'] );
        try {
            $service = $request['current_service'];
            $sv_id = isset($service['ServiceId']) ? $service['ServiceId'] : 0;
            $sv_params = [
                'ServiceId'   	=> $sv_id,
                'ServiceName'   => filter_var($service['ServiceName'], FILTER_SANITIZE_STRING),
                'Phone'         => filter_var($service['Phone'], FILTER_SANITIZE_STRING),
                'Email'         => filter_var($service['Email'], FILTER_SANITIZE_EMAIL),
                'Description'   => $service['Description'],
                'Notes'         => isset($service['Notes']) ? $service['Notes'] : '',
                'Location'      => filter_var($service['Location'], FILTER_SANITIZE_STRING),
                'URL'           => filter_var($service['URL'], FILTER_SANITIZE_URL),
                'ServiceProviderId' => $request["service_provider"],
                'Wait'           => filter_var($service['Wait'], FILTER_SANITIZE_STRING),
                'ServiceLevelId' => $request['service_level'],
                'ServiceTypeId'  => $request['service_type'] ,
                'OpenningHrs'    => filter_var($service['OpenningHrs'], FILTER_SANITIZE_STRING),
                'Status'         => $request['status'] ? 1 : 0 , // TODO
                'Specialist'     => false,
            ];

            $service_obj = new Service();
            $service_obj->saveServices($sv_params, $sv_id,$request);
            return ['success' => 'success' , 'message' => 'General Settings saved.'];

        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }

    /**
     * Update service intake options of an existing service
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse service listing page with success/error message
     */
    public function storeIntakeOptions(Request $request)
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp', 'AdminSpClc'] );

        try {
            if(isset($request['sv_id']) && $request['sv_id'] > 0) {
                $sv_id = $request['sv_id'];

                $referral_conditions  = (isset($sv_id, $request['referral_conditions']) ? $request['referral_conditions'] : []);
                $booking_conditions   = (isset($request['booking_conditions']) ? $request['booking_conditions'] : []);
                $e_referral_conditions = (isset($request['e_referral_conditions']) ? $request['e_referral_conditions'] : []);
                $e_referral_forms      = (isset($request['e_referral_forms']) ? $request['e_referral_forms'] : []);
                $booking_question      = (isset($request['booking_question']) ? $request['booking_question'] : []);

                $service = new Service();
                $service->saveServiceActions($sv_id, $referral_conditions, $booking_conditions, $e_referral_conditions);
                $service->saveServiceEReferrals($sv_id, $e_referral_forms);
                $service->saveServiceBookingQuestions($sv_id, $booking_question);
                return ['success' => 'success' , 'message' => 'Intake options saved.'];

            } else {
                return ['success' => 'error' , 'message' => 'Please save service first.'];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }


    /**
     * Update service Client Eligibility Questions and Booking Questions of an existing service
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse service listing page with success/error message
     */
    public function storeClientEligibility(Request $request)
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp', 'AdminSpClc'] );

        try {
            if(isset($request['sv_id']) && $request['sv_id'] > 0) {

                $sv_id = $request['sv_id'];

                $vulnerability    = (isset($request['vulnerability']) ? $request['vulnerability'] : []);

                $service = new Service();
                $service->saveServiceEligibilityQuestions($sv_id, $vulnerability);

                return ['success' => 'success' , 'message' => 'Client Matters saved.'];
            } else {
                return ['success' => 'error' , 'message' => 'Please save service first.'];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }

    /**
     * Update service Legal Matter and Legal Matters conditions / eligilibility of an existing service
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse service listing page with success/error message
     */
    public function storeLegalMatter(Request $request)
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp', 'AdminSpClc'] );

        try {
            if(isset($request['sv_id']) && $request['sv_id'] > 0) {

                $sv_id = $request['sv_id'];

                $matters    = (isset($request['matters']) ? $request['matters'] : []);

                $service = new Service();
                $matters_ids = array_column($matters, 'id');
                // Save the legal matters
                $result = $service->saveServiceMatters($sv_id, $matters_ids);
                if($result['message']){
                    // Get the legal Matter Service
                    $matter_service_obj     = new MatterService();
                    $matter_services_list   = $matter_service_obj->getMatterServiceBySvID($sv_id);

                    $matter_service_answer = new MatterServiceAnswer();
                    // Save the Legal Matter Conditions.
                    $matter_service_answer->processMatterServiceAnswers( $matters , $sv_id , $matter_services_list);
                    // Save the Legal Matter Eligibility Criteria
                    $matter_service_answer->processVulnerabilityMatterServiceAnswers( $matters , $matter_services_list );


                }
                return ['success' => 'success' , 'message' => 'Client Matters saved.'];
            } else {
                return ['success' => 'error' , 'message' => 'Please save service first.'];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }

    /**
     * Show the form for creating a new service
     * @return view service creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp', 'AdminSpClc'] );

        $service_type_obj   = new ServiceType();
        $service_types      = $service_type_obj->getAllServiceTypes();

        $service_level_obj  = new ServiceLevel();
        $service_levels     = $service_level_obj->getAllServiceLevels();

        $service_provider_obj   = new ServiceProvider();
        $service_providers      = $service_provider_obj->getAllServiceproviders();

        $matter_type_obj    = new Matter();
        $matters            = $matter_type_obj->getAllMatters();

        $vulnerability_obj = new Vulnerability();
        $vulnertability_questions = $vulnerability_obj->getAllVulnerabilityQuestions();

        $e_referral_obj = new EReferral();

        $user = Auth::user();
        if ( $user->sp_id != 0 ) {
             //Just return current service provider
            $service_provider = $service_providers[array_search($user->sp_id, array_column($service_providers, 'ServiceProviderId'))];
            $service_providers = [$service_provider];
        }
        return view( "service.create", compact( 'service_types','service_levels','service_providers', 'matters', 'vulnertability_questions' ) );
    }

    /**
     * Remove the specified service from data base.
     * @param  int $sv_id service id
     * @return mixed service listing page with success/error message
     */
    public function destroy($sv_id)
    {
        Auth::user()->authorizeRoles( 'Administrator' );

        $service  = new Service();
        $response = $service->deleteService($sv_id);

        return redirect('/service')->with( $response['success'], $response['message'] );
    }

    /**
     * List all service outdated in a give period of time
     * @param int $days number of days (should be a positive number)
     * @return array list of all service outdated in a give period of time
     */
    public function listWithoutUpdate($days)
    {
        $service = new Service();
        $result  = $service->getServicesNotUpdated($days);

        return [ 'data' => $result ];
    }

    /**
     * List all service
     * @return array list of all service
     */
    public function list()
    {
        $service = new Service();
        $result  = $service->getAllServices();
        return [ 'data' => $result ];
    }

    /**
     * Get Services information in a data table format
     *
     * @param Request $request
     * @return Services    Users with paginator
     */
    public function listTable(Request $request)
    {
        $service = new Service();
        $services = $service->getServiceTable($request);

        if($services['success'] === 'success') {
            return $services['data'];
        }
        return ['errors' => $services['message']];
    }

    /**
     * List all service by service provider and user service provider
     * @return array list of all service filtered by service provider and user service provider
     */
    public function listServicesSP( $sp_id )
    {
        $user = Auth::user();

        $service = new Service();
        $result  = $service->getAllServicesByServiceProviderAndUSerSP( $sp_id, $user->sp_id );

        return $result;
    }

    /**
     * Get service by id
     * @return object service
     */
    public function listServiceById( $sv_id )
    {
        $service_obj = new Service();
        $service  = $service_obj->getServiceInformationByID($sv_id);
        return response()->json($service);
    }

    /**
     * Send sign up email to orbit team
     *
     */
    public function sendRequestEmail()
    {
        $user = Auth::user();
        $request = request()->all();
        $request['user'] = $user;
        Mail::to('orbitteam@vla.vic.gov.au')->send( new RequestEmail( $request ) );
    }

}
