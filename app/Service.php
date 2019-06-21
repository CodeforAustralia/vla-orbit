<?php
namespace App;

use Illuminate\Support\Facades\Mail;
use App\Mail\ServiceNotification;
use App\ServiceProvider;
use App\User;
use App\Catchment;
use App\EReferral;
use App\Log;
use App\MatterService;
use App\MatterServiceAnswer;
use App\ServiceAction;
use App\ServiceBookingQuestions;
use App\Vulnerability;
use DateTime;
/**
 * Service model for the service functionalities
 * @author Christian Arevalo
 * @version 1.1.0
 * @see  OrbitSoap
 */
Class Service extends OrbitSoap
{
    /**
     * Get all services
     * @return array $services services
     */
    public function getAllServices()
    {

        $services = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetAllOrbitServicesasJSON')
                                    ->GetAllOrbitServicesasJSON()
                                    ->GetAllOrbitServicesasJSONResult
                                    , true
                                );

        //Sort by key on a multidimentional array
        usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); });

        return $services;

    }
    /**
     * Get all services by services provider
     * @param  int    $sp_id    service provider id
     * @return array  $services services by service provider
     */
    public function getAllServicesBySP( $sp_id )
    {

        $info = [
                    'ServiceProviderId'    => $sp_id
                ];

        $services = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetAllOrbitServicesByServiceProviderasJSON')
                                    ->GetAllOrbitServicesByServiceProviderasJSON( $info )
                                    ->GetAllOrbitServicesByServiceProviderasJSONResult
                                    , true
                                );

        //Sort by key on a multidimentional array
        usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); });

        return $services;

    }
    /**
     * Get all services by service provider and service provider user
     * @param  int    $sp_id      service provider id
     * @param  int    $user_sp_id service provider user id
     * @return array  $services   services by service provider
     */
	public function getAllServicesBySPAndUserSP( $sp_id, $user_sp_id )
	{

        $info = [
                    'OwnerProviderId'   => $sp_id ,
                    'ServiceProviderId' => $user_sp_id
                ];

        $services = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetAllEligibleOrbitServicesByProviderasJSON')
                                    ->GetAllEligibleOrbitServicesByProviderasJSON( $info )
                                    ->GetAllEligibleOrbitServicesByProviderasJSONResult
                                    , true
                                );

        //Sort by key on a multidimentional array
        usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); });

        return $services;

	}
    /**
     * Create or update a service
     * @param  Object   $sv_params  service details
     * @param Array     $request    Form paramenters with additional service information
     * @return Array    success or error message
     */
    public function saveService( $sv_params, $request )
    {
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $sv_params['CreatedBy'] = auth()->user()->name;
        $sv_params['UpdatedBy'] = auth()->user()->name;
        $sv_params['CreatedOn'] = $date_time;
        $sv_params['UpdatedOn'] = $date_time;

        $catchment_info = [];
        if( isset($request['lga']) && !is_null($request['lga']) ){
            $catchment_info['lga'] = $request['lga'];
        }
        if( isset($request['postcodes']) && !is_null($request['postcodes']) ){
            $catchment_info['postcodes'] = $request['postcodes'];
        }
        if( isset($request['suburbs']) && !is_null($request['suburbs']) ){
            $catchment_info['suburbs'] = $request['suburbs'];
        }

        $info = [ 'ObjectInstance' => $sv_params ];

        try {
            $response = $this
                        ->client
                        ->ws_init('SaveOrbitService')
                        ->SaveOrbitService( $info );
            // Redirect to index
            if ( $response->SaveOrbitServiceResult >= 0 ) {
                $sv_id = $response->SaveOrbitServiceResult;

                $matters       = (isset($request['matters']) ? $request['matters'] : []);
                $vulnerability = (isset($request['vulnerability']) ? $request['vulnerability'] : []);
                $question      = (isset($request['question']) ? $request['question'] : []);
                $vulnerability_matter = (isset($request['vulnerability_matter']) ? $request['vulnerability_matter'] : []);
                $booking_question     = (isset($request['booking_question']) ? $request['booking_question'] : []);
                $referral_conditions  = (isset($sv_id, $request['referral_conditions']) ? $request['referral_conditions'] : []);
                $booking_conditions   = (isset($request['booking_conditions']) ? $request['booking_conditions'] : []);
                $e_referral_conditions = (isset($request['e_referral_conditions']) ? $request['e_referral_conditions'] : []);
                $e_referral_forms      = (isset($request['e_referral_forms']) ? $request['e_referral_forms'] : []);

                self::saveServiceMatters($sv_id, $matters);
                self::saveServiceCatchments($sv_id, $catchment_info);
                self::saveServiceQuestions($sv_id, $vulnerability, $question, $vulnerability_matter, $booking_question);
                self::saveServiceActions($sv_id, $referral_conditions, $booking_conditions, $e_referral_conditions);
                self::saveServiceEReferrals($sv_id, $e_referral_forms);

                return [ 'success' => 'success' , 'message' => 'Service saved.', 'data' => $sv_id ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }

    /**
     * Create or update a service
     * @param  Object   $sv_params  service details
     * @param Array     $request    Form paramenters with additional service information
     * @return Array    success or error message
     */
    public function saveServices( $sv_params, $sv_id, $request )
    {
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $sv_params['CreatedBy'] = auth()->user()->name;
        $sv_params['UpdatedBy'] = auth()->user()->name;
        $sv_params['CreatedOn'] = $date_time;
        $sv_params['UpdatedOn'] = $date_time;

        $catchment_info = [];
        if( isset($request['lga']) && !is_null($request['lga']) ){
            $catchment_info['lga'] = $request['lga'];
        }
        if( isset($request['postcodes']) && !is_null($request['postcodes']) ){
            $catchment_info['postcodes'] = $request['postcodes'];
        }
        if( isset($request['suburbs']) && !is_null($request['suburbs']) ){
            $catchment_info['suburbs'] = $request['suburbs'];
        }
        $info = [ 'ObjectInstance' => $sv_params ];

        try {
            $response = $this
                        ->client
                        ->ws_init('SaveOrbitService')
                        ->SaveOrbitService( $info );
            // Redirect to index
            if ( $response->SaveOrbitServiceResult >= 0 ) {
                $log = new Log();
                $log::record( 'UPDATE', 'service', $sv_id, ['general_settings' => $sv_params] );
                $log::record( 'UPDATE', 'service', $sv_id, ['catchment_info' => $catchment_info] );
                self::saveServiceCatchments($sv_id, $catchment_info);
                return [ 'success' => 'success' , 'message' => 'Service saved.', 'data' => $sv_id ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     * Delete a service
     * @param  int    $sv_id service id
     * @return array         success or error message
     */
    public function deleteService($sv_id)
    {

        // Create call request
        $info = [ 'RefNumber' => $sv_id ];

        try {
            $response = $this->client->ws_init('DeleteOrbitService')->DeleteOrbitService( $info );
            if ( $response->DeleteOrbitServiceResult ) {
                $log = new Log();
                $log::record( 'DELETE', 'service', $sv_id, 'Service deleted.' );
                return array( 'success' => 'success' , 'message' => 'Service deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        } catch ( \Exception $e ) {
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );
        }

    }
    /**
     * Gete service by service id
     * @param  int    $sv_id service id
     * @return array         service or error message
     */
    public function getServiceByID($sv_id)
    {
        // Create call request
        $info = [ 'ServiceId' => $sv_id ];

        try {
            $response =     $this
                            ->client
                            ->ws_init('GetOrbitServicesWithMattersByIdasJSON')
                            ->GetOrbitServicesWithMattersByIdasJSON( $info );

            if ( $response->GetOrbitServicesWithMattersByIdasJSONResult ) {
                return [ 'success' => 'success' , 'message' => 'Service.', 'data' => $response->GetOrbitServicesWithMattersByIdasJSONResult ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }

    /**
     * Get complete information of a service by ID
     * @param  Int    $sv_id service id
     * @return Array         service information
     */
    public function getServiceInformationByID($sv_id)
    {
        try {
            $result  = self::getServiceByID($sv_id)['data'];
            $service = json_decode($result)[0];
            //Sort service matters and sort alphabetically
            usort($service->ServiceMatters, function($a, $b){ return strcasecmp($a->MatterName, $b->MatterName); });
            //Get vulnerability question information
            $vulnerability_obj = new Vulnerability();
            $vulnertability_questions = $vulnerability_obj->getAllVulnerabilityQuestions();
            $serv_vuln = $service;
            //Get vulnerability Questions of this service
            $service_vulnerabilities = [];
            foreach ( $serv_vuln->ServiceVulAnswers as $vulnerability) {
                $vul_pos = array_search( $vulnerability->QuestionId,  array_column( $vulnertability_questions, 'QuestionId' ) );
                $service_vulnerabilities[] = $vulnertability_questions[$vul_pos]['QuestionLabel'];
            }
            asort($service_vulnerabilities);
            $service->vulnerabilities =  empty($service_vulnerabilities) ? ['None'] : array_values( $service_vulnerabilities );
            //Get service catchments and sort alphabetically
            $service_catchments = [];
            foreach ( $serv_vuln->ServiceCatchments as $catchments) {
                $service_catchments[$catchments->CatchmentSuburb] = $catchments->CatchmentSuburb;
            }
            asort($service_catchments);
            $service->catchments = array_values($service_catchments);

            return $service;
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     * Get al service by service provider
     * @param  int    $sp_id        service provider id
     * @return array  $sp_services  services by service provider
     */
    public function getAllServicesByServiceProvider( $sp_id )
    {
        $services = self::getAllServices();

        $sp_services = [];

        foreach ( $services as $service ) {
            if ( $service['ServiceProviderId'] == $sp_id ){
                $sp_services[] = $service;
            }
        }

        return $sp_services;
    }
    /**
     * Get all services by service provider and service provider user
     * @param  int    $sp_id             service provider id
     * @param  int    $user_sp_id        service provider user id
     * @return array  $filtered_services services by service provider and service provider user
     */
    public function getAllServicesByServiceProviderAndUSerSP( $sp_id, $user_sp_id )
    {
        $services = self::getAllServicesBySP( $sp_id );

        $services_with_actions = self::getAllServicesBySPAndUserSP( $sp_id, $user_sp_id );

        $filtered_services = [];

        foreach ( $services as $service ) {

            $service_position = array_search( $service['ServiceId'],  array_column( $services_with_actions, 'ServiceId' ) );
            if ( $service_position === false ) { //The user has an action in the serice

                $service['ServiceActions'] = [];
                if ( auth()->user()->sp_id === 0 ) {
                    $service['ServiceActions'] = array(["Action" => "ALL"]);
                }
                $filtered_services[] = $service;
            } else {
                $filtered_services[] = $services_with_actions[$service_position];
            }
        }

        return $filtered_services;
    }

    /**
     * Get services information to be displayed in a data tabe format
     *
     * @param Request $request
     * @return Services
     */
    public function getServiceTable($request)
    {
        try {
            $params = [
                'PerPage' => (isset($request['per_page']) && $request['per_page'] ? $request['per_page'] : '') ,
                'Page' => (isset($request['page']) && $request['page'] ? $request['page'] - 1 : 0) ,
                'SortColumn' => (isset($request['column']) && $request['column'] ? self::mapServicesColumnsToFields($request['column']) : '') ,
                'SortOrder' => (isset($request['order']) && $request['order'] ? $request['order'] : '') ,
                'ColumnSearch' => '',
                'Search' => (isset($request['search']) && $request['search'] ? $request['search'] : '') ,
            ];

            $services = json_decode(
                                        $this
                                        ->client
                                        ->ws_init('GetAllServicesinBatchasJSON')
                                        ->GetAllServicesinBatchasJSON( $params )
                                        ->GetAllServicesinBatchasJSONResult
                                        , true
                                    );
            $services['data'] = self::mapServicesToFields($services['data']);
            return [ 'success' => 'success' , 'data' => $services ];
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }

    /**
     * Map services object returned from Webservices with fields in Orbit
     *
     * @param array $services Set of services returned by the web service
     * @return array Mapped array with values in Orbit
     */
    public static function mapServicesToFields($services)
    {
        $mapped_services = [];
        foreach ($services as $service) {
            $mapped_services[] = [
                                    'sv_id' => $service['ServiceId'],
                                    'name' => $service['ServiceName'],
                                    'service_provider' => $service['ServiceProviderName'],
                                    'phone' => $service['Phone'],
                                    'email' => $service['Email'],
                                    'service_type' => $service['ServiceTypeName'],
                                    'service_level' => $service['ServiceLevelName'],
                                    'sp_id' => $service['ServiceProviderId'],
                                ];
        }
        return $mapped_services;
    }

    /**
     * Translate column names to names in tables
     *
     * @param String $column Column name
     * @return String   Column name in table
     */
    public static function mapServicesColumnsToFields($column) {
        switch ($column) {
            case 'name':
                return 'sv_name';
                break;
            case 'service_provider':
                return 'sp_name';
                break;
            case 'phone':
                return 'sv_phone';
                break;
            case 'email':
                return 'sv_email';
                break;
            case 'service_type':
                return 'st_name';
                break;
            case 'service_level':
                return 'sl_name';
                break;
            default:
                return '';
                break;
        }
    }

    /**
     * Save service matters
     *
     * @param Int $sv_id        Service ID
     * @param Array $matters    Legal matters IDs that are offered within this service
     * @return void
     */
    public static function saveServiceMatters($sv_id, $matters)
    {
        $result = true;
        $matter_service_obj = new MatterService();
        $matter_service_obj->deleteMatterServiceByID( $sv_id ) ;

        if ( !empty( $matters ) ) {
            $result = $matter_service_obj->saveMattersService( $sv_id, $matters );
        }
        return $result;
    }

    /**
     * Save service catchments
     *
     * @param Int $sv_id            Service ID
     * @param Array $catchment_info Catment information such us postcode, LGC or Suburb
     * @return void
     */
    public static function saveServiceCatchments($sv_id, $catchment_info)
    {
        $catchment = new Catchment();
        $catchment->setCatchmentsOnRequest( $catchment_info, $sv_id );
    }

    /**
     * Save service questions of different types, can be empty if there are no questions
     *
     * @param Int $sv_id        Service ID
     * @param Array $vulnerability_questions    Question IDs of querstions that can be offered in this service
     * @param Array $vulnerability_matter       Question IDs of querstions that can be offered in this service
     * @param Array $regular_questions          Question IDs of querstions that can be offered in this service
     * @param Array $sv_booking_questions       Question IDs of querstions that can be offered in this service
     * @return void
     */
    public static function saveServiceQuestions($sv_id, $vulnerability_questions, $vulnerability_matter, $regular_questions, $sv_booking_questions)
    {
        // Delete previous vul. answers
        $vulnerability_obj = new Vulnerability();
        $vulnerability_obj->deleteVulnerabilityByServiceID( $sv_id );
        if ( !empty( $vulnerability_questions ) ) {
            $vul_questions = array_keys( $vulnerability_questions );
            // Save vul. answers
            $vulnerability_obj->saveVulnerabilityQuestions( $sv_id, $vul_questions );
        }

        if ( !empty( $regular_questions ) ) {
            $matter_service_answer = new MatterServiceAnswer();
            $matter_service_answer->processMatterServiceAnswer( $regular_questions, $sv_id );
            $matter_service_answer->processVulnerabilityMatterServiceAnswer( $vulnerability_matter, $sv_id );
        }

        // Delete previous service booking questions
        $service_booking_questions = new ServiceBookingQuestions();
        $service_booking_questions->deleteServiceBookingQuestionsByServiceId($sv_id);
        if ( !empty( $sv_booking_questions ) ) {
            foreach ($sv_booking_questions as $qu_id => $sv_booking_question) {
                if( !is_null($sv_booking_question['operator']) && !is_null($sv_booking_question['answer']) ){
                    $sbq_params[] = [
                                        'ServiceId' => $sv_id,
                                        'QuestionId' => $qu_id,
                                        'Operator' => $sv_booking_question['operator'],
                                        'QuestionValue' => $sv_booking_question['answer'],
                                        'ServiceBookingQuestionId' => 0
                                    ];
                }
            }
            $service_booking_questions->saveServiceBookingQuestions( $sbq_params );
        }
    }

    /**
     * Save service actions per service provider
     *
     * @param Int $sv_id        Service ID
     * @param Array $referral_conditions        Service provider IDs who can refer to this service
     * @param Array $booking_conditions         Service provider IDs who can book to this service
     * @param Array $e_referral_conditions      Service provider IDs who can eRefer to this service
     * @return void
     */
    public static function saveServiceActions($sv_id, $referral_conditions, $booking_conditions, $e_referral_conditions)
    {
        $service_action = new ServiceAction();
        $service_action->deleteAllActionsByService( $sv_id );
        $service_action->saveServiceAction( 'REFER', $sv_id, $referral_conditions );
        $service_action->saveServiceAction( 'BOOK', $sv_id, $booking_conditions );
        $service_action->saveServiceAction( 'E_REFER', $sv_id, $e_referral_conditions );
        $log = new Log();
        $log::record( 'UPDATE', 'service', $sv_id, ['actions_refer' => $referral_conditions] );
        $log::record( 'UPDATE', 'service', $sv_id, ['actions_book' => $booking_conditions] );
        $log::record( 'UPDATE', 'service', $sv_id, ['actions_e_refer' => $e_referral_conditions] );

    }

    /**
     * Save eReferral conditions
     *
     * @param Int $sv_id                Service ID
     * @param Array $e_referral_forms   eReferral form IDs who can be offered in this service
     * @return void
     */
    public static function saveServiceEReferrals($sv_id, $e_referral_forms)
    {
        $e_referral_obj = new EReferral();
        $e_referral_obj->deleteAllEReferralByServiceId( $sv_id );
        $e_referral_obj->saveAllFormsInService( $sv_id, $e_referral_forms );
        $log = new Log();
        $log::record( 'UPDATE', 'service', $sv_id, ['e_referral_forms' => $e_referral_forms] );
    }

    /**
     * Save service questions, can be empty if there are no questions
     *
     * @param Int $sv_id        Service ID
     * @param Array $vulnerability_questions    Question IDs of querstions that can be offered in this service
     * @return void
     */
    public static function saveServiceEligibilityQuestions($sv_id, $vulnerability_questions)
    {
        // Delete previous vul. answers
        $vulnerability_obj = new Vulnerability();
        $vulnerability_obj->deleteVulnerabilityByServiceID( $sv_id );
        if ( !empty( $vulnerability_questions ) ) {
            // Save vul. answers
            $vulnerability_obj->saveVulnerabilityQuestions( $sv_id, $vulnerability_questions );
            $log = new Log();
            $log::record( 'UPDATE', 'service', $sv_id, ['eligibility_questions' => $vulnerability_questions] );
        }
    }

    /**
     * Save service booking questions, can be empty if there are no questions
     *
     * @param Int $sv_id        Service ID
     * @param Array $sv_booking_questions       Question IDs of querstions that can be offered in this service
     * @return void
     */
    public static function saveServiceBookingQuestions($sv_id, $sv_booking_questions)
    {
        // Delete previous service booking questions
        $service_booking_questions = new ServiceBookingQuestions();
        $service_booking_questions->deleteServiceBookingQuestionsByServiceId($sv_id);
        if ( !empty( $sv_booking_questions ) ) {
            $sbq_params = [];
            foreach ($sv_booking_questions as $sv_booking_question) {
                if( !is_null($sv_booking_question['Operator']) && !is_null($sv_booking_question['QuestionValue']) && !is_null($sv_booking_question['QuestionId'])){
                    $sbq_params[] = [
                                        'ServiceId' => $sv_id,
                                        'QuestionId' => $sv_booking_question['QuestionId'],
                                        'Operator' => $sv_booking_question['Operator'],
                                        'QuestionValue' => $sv_booking_question['QuestionValue'],
                                        'ServiceBookingQuestionId' => 0
                                    ];
                }
            }
            $service_booking_questions->saveServiceBookingQuestions( $sbq_params );
            $log = new Log();
            $log::record( 'UPDATE', 'service', $sv_id, ['booking_questions' => $sbq_params] );
        }

    }

    /**
     * Get a log of service notes
     *
     * @param int $sv_id Service ID
     * @return array
     */
    public function getServiceNotesLogs($sv_id)
    {
        $log = new \App\Log();
        $logs = $log->getLogByDataCondition('service', $sv_id, ['data->general_settings->Notes', '!=', '']);
        $notes = [];
        $scope_notes = []; //Keep track of each note in log and allows only the new ones
        foreach ($logs as $register) {
            if(!in_array($register['data']['general_settings']['Notes'], $scope_notes)) {
                $notes[] = [
                                'created_at' => date("d-m-Y", strtotime($register['created_at'])),
                                'note' => $register['data']['general_settings']['Notes']
                            ];
                $scope_notes[] = $register['data']['general_settings']['Notes'];
            }
        }
        return $notes;
    }

    /**
     * Get all outdated services
     *
     * @return void
     */
    public function getServicesNotUpdated($date_limit = '90')
    {
        $services = self::getAllServices();
        $data = [];
        $date_limit = '-' . $date_limit . ' days';
        $date_limit = new DateTime(date('d-m-Y H:i:s', strtotime( $date_limit )));
        $two_minutes_before = new DateTime(date('d-m-Y H:i:s', strtotime('-2 minutes')));

        //Save notified ones in log
        //Remove notified ones from date of notification
        foreach ($services as $service) {

            $log = new Log();
            $service['UpdatedOn'] = \App\Http\helpers::transformMicrosoftDateToDate($service['UpdatedOn']);
            $service['CreatedOn'] = \App\Http\helpers::transformMicrosoftDateToDate($service['CreatedOn']);
            $service_created =  new DateTime(date('d-m-Y H:i:s', strtotime($service['CreatedOn'])));
            $service_last_update =  new DateTime(date('d-m-Y H:i:s', strtotime($service['UpdatedOn'])));
            $last_notification = $log->getServiceLastNotification($service['ServiceId']);

            if( $date_limit->format('Y-m-d" H:i:s') > $service_last_update->format('Y-m-d" H:i:s')
                || ( $two_minutes_before->format('Y-m-d" H:i:s') < $service_last_update->format('Y-m-d" H:i:s')
                    && $date_limit->format('Y-m-d" H:i:s') > $service_created->format('Y-m-d" H:i:s'))){

                $service['UpdatedOn'] = ($service_last_update->format('Y-m-d"') == $two_minutes_before->format('Y-m-d"')) ? '' : $service['UpdatedOn'];
                $data[] = [
                    'ServiceId' => $service['ServiceId'],
                    'ServiceName' => $service['ServiceName'],
                    'Email' => $service['Email'],
                    //'ServiceProviderId' => $service['ServiceProviderId'],
                    'ServiceProviderName' => $service['ServiceProviderName'],
                    'ServiceProviderTypeName' => $service['ServiceProviderTypeName'],
                    //'CreatedOn' => $service['CreatedOn'],
                    'UpdatedOn' => $service_last_update->format('d-m-Y'),
                    'last_notification' => !is_null($last_notification['data']) ?  $last_notification['data']['date'] : 'Not sent'
                ];

            }
        }

        usort($data, function($a, $b){ return strcmp(strtotime($a["UpdatedOn"]), strtotime($b["UpdatedOn"])); });
        return $data;
    }
    /**
     * Send Notification about the out of date services.
     *
     * @param Array $service_ids
     * @return void
     */
    public function sendServiceNotificacion($service_ids, $template)
    {
        $service_provider_obj   = new ServiceProvider();
        $service_providers      = $service_provider_obj->getAllServiceproviders();
        $users = User::select('id', 'email', 'sp_id')->with('roles')->get();
        $services = self::getAllServices();

        $prefix = '</em><br><em>Please do not reply to this email.</em><br><hr><br>';
        $suffix = '<em>If you wish to contact us, please do not reply to this message. Replies to this message will not be read or responded to.</em><br><br><br><p classname = "orbitprefix" style="background: #f5f8fa; padding-top: 15px;box-sizing: border-box; color: #aeaeae; font-size: smaller; text-align: center; margin:0px">© 2019 '. ucfirst(config('app.name')) .'. All rights reserved.</p><p classname = "emailprefix" style=" background: #f5f8fa; padding: 15px;box-sizing: border-box; color: #74787e;line-height: 1.4; margin: 0px; font-size: small;">Disclaimer: The material in this email is a general guide only. It is not legal advice. The law changes all the time and the general information in this email may not always apply to your own situation. The information in this email has been carefully collected from reliable sources. The sender is not responsible for any mistakes or for any decisions you may make or action you may take based on the information in this email. Some links in this email may connect to websites maintained by third parties. The sender is not responsible for the accuracy or any other aspect of information contained in the third-party websites. This email is intended for the use of the person or organisation it is addressed to and must not be copied, forwarded or shared with anyone without the sender’s consent (agreement). If you are not the intended recipient (the person the email is addressed to), any use, sharing, forwarding or copying of this email and/or any attachments is strictly prohibited. If you received this e-mail by mistake, please let the sender know and please destroy the original email and its contents.</p><br><br>';

        foreach ($service_ids as $service_id) {
            $email_info = [];
            $email_info = self::getServiceEmailAndType( $service_id, $service_providers, $users, $services);
            $email_info['message'] = $prefix . $template . $suffix;
            $email_info['subject'] = "Test Email";
            if($email_info['email'] != '') {
                Mail::to( $email_info['email'] )->send( new ServiceNotification( $email_info ) );
                $log = new Log();
                $log::record( 'CREATE', 'service_notification', $service_id, ['date' => date("Y-m-d")] );
            }
        }
        return 1;
    }
    /**
     * Get the email of the Admin of the service
     *
     * @param Array $service_ids
     * @return void
     */
    private function getServiceEmailAndType ($service_id,  $service_providers, $users, $services)
    {
        $result=['email'=>'', 'service_provider_type'=> ''];
        $service = array_filter($services, function($service) use ($service_id) {
                if ( $service['ServiceId'] == $service_id ) {
                    return $service;
                }
            });
        $service=array_shift($service);
        $service_provider = array_filter($service_providers, function($service_provider) use ($service) {
                if ( $service_provider['ServiceProviderId'] == $service['ServiceProviderId'] ) {
                    return $service_provider;
                }
            });
        $service_provider = array_shift($service_provider);
        $result['service_provider_type'] = $service_provider['ServiceProviderTypeName'];
        foreach($users as $user) {
            if($service_provider['ServiceProviderId'] ==  $user->sp_id
            && ($user->roles()->first()->name == "AdminSp" || $user->roles()->first()->name == "AdminSpClc")) {
                $result['email'] = $user->email;
            }
        }
        return $result;
    }

}

