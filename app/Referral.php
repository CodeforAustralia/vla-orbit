<?php
namespace App;

use Illuminate\Support\Facades\Mail;
use App\Log;
use App\Mail\ReferralEmail;
use App\Mail\ReferralSms;
use App\Mail\ReferralPanelLawyerEmail;
use App\Mail\ReferralPanelLawyerSms;
use App\Vulnerability;
use App\ServiceProviderType;
use App\Matter;
use Auth;

/**
 * Referral Model.
 *
 * @author Christian Arevalo and Sebastian Currea
 * @version 1.2.0
 * @see  OrbitSoap
 */
class Referral extends OrbitSoap
{
    const REFERRAL_LIMIT = 2000;

    /**
     * Get all Referrals
     * @return array Array with Referrals
     */
    public function getAllReferrals()
    {

        $user = Auth::user();
        if ( $user->sp_id != 0 ){

            $info['ServiceProviderId'] = $user->sp_id;
            $referrals = $this->client
                         ->ws_init( 'GetAllReferralsByServiceProvider' )
                         ->GetAllReferralsByServiceProvider( $info )
                         ->GetAllReferralsByServiceProviderResult
                         ->Referral;
        } else {
            $referrals = $this->client
                         ->ws_init( 'GetAllReferrals' )
                         ->GetAllReferrals()
                         ->GetAllReferralsResult
                         ->Referral;
        }

        if ( sizeof($referrals) > 1 ){
            usort($referrals, function($a, $b){ return $a->RefNo < $b->RefNo; });
            $referrals = array_slice( $referrals, 0,  self::REFERRAL_LIMIT );
        }else {
            $referrals = array($referrals);
        }

        return $referrals;
    }
    /**
     * Get all outbound referrals
     * @return array Array with Referrals
     */
    public function getAllOutboundReferrals()
    {

        $user = Auth::user();
        if ( $user->sp_id != 0 ){

            $info['OutServiceProviderId'] = $user->sp_id;
            $referrals = $this->client
                         ->ws_init( 'GetAllReferralsByOutServiceProvider' )
                         ->GetAllReferralsByOutServiceProvider( $info )
                         ->GetAllReferralsByOutServiceProviderResult
                         ->Referral;
        } else {
            $referrals = $this->client
                        ->ws_init( 'GetAllReferrals' )
                        ->GetAllReferrals()
                        ->GetAllReferralsResult
                        ->Referral;
        }

        if ( sizeof($referrals) > 1 ) {

            usort($referrals, function($a, $b){ return $a->RefNo < $b->RefNo; });
            $referrals = array_slice( $referrals, 0,  self::REFERRAL_LIMIT );
        } else {
            $referrals = array($referrals);
        }

        return $referrals;
    }
    /**
     * Get all referrals by service provider
     * @param  int    $sp_id     service provider id
     * @return array  $referrals referrals by service provider
     */
    public function getAllReferralsBySP( $sp_id )
    {

        $info = [ 'ServiceProviderId' => $sp_id];

        $referrals = json_decode(
                                    $this->client
                                    ->ws_init( 'GetAllReferralsByServiceProviderasJSON' )
                                    ->GetAllReferralsByServiceProviderasJSON( $info )
                                    ->GetAllReferralsByServiceProviderasJSONResult,
                                    true
                                );

        if ( sizeof($referrals) > 1 ) {
            usort($referrals, function($a, $b){ return $a->RefNo < $b->RefNo; });
            $referrals = array_slice( $referrals, 0,  self::REFERRAL_LIMIT );
        } else {
            $referrals = [$referrals];
        }

        return $referrals;
    }
    /**
     * Save referrals and send email or sms message
     * @param  Object $referral         referral
     * @param  Object $service_provider service provider
     * @return array                    Array with error or success message
     */
    public function saveReferral( $referral, $service_provider )
    {

        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $referral['CreatedBy'] = auth()->user()->name;
        $referral['UpdatedBy'] = auth()->user()->name;
        $referral['CreatedOn'] = $date_time;
        $referral['UpdatedOn'] = $date_time;
        $referral['Mobile']    = ( $referral['Mobile'] == '' ? '000000000' : $referral['Mobile'] );
        $referral['Email']     = ( $referral['Email'] == '' ? 'N/P' : $referral['Email'] );
        $referral['Notes']     = ( ( !isset( $referral['Notes'] ) || $referral['Notes'] === '') ? 'N/P' : $referral['Notes'] );
        $referral['RefNo']     = 0;
        $referral['SentEmail'] = 0;
        $referral['SentMobile'] = 0;
        $referral['ReferralAnswers'] = self::getAnswersFromSession();

        $services = session('matches');
        $service = $services[ $referral['ServiceId'] ];
        $service['sendingServiceProvider'] = $service_provider;
        $service['Nearest'] = $referral['Nearest'];
        //Get the service legal matter
        $matter_obj = new Matter();
        $matter = $matter_obj->getAllMatterById(session('mt_id'));
        $service['LegalMatter'] = $matter->MatterName;

        if ( $referral['Email'] != 'N/P' && $referral['SafeEmail'] != 0 ) {
            $referral['SentEmail'] = 1;
        }

        if ( $referral['Mobile'] != '' && $referral['SafeMobile'] != 0 ) {
            $referral['SentMobile'] = 1;
        }

        $info = [ 'ObjectInstance' => $referral ];

        try {
            $response = $this->client
                        ->ws_init( 'SaveReferral' )
                        ->SaveReferral( $info );
            if ( $response->SaveReferralResult ) {
                $service['RefNo'] = $response->SaveReferralResult;
                if ( $referral['Email'] != 'N/P' && $referral['SafeEmail'] != 0 ) {
                    if ( $referral['Nearest'] != '' ) {
                        Mail::to( $referral['Email'] )->send( new ReferralPanelLawyerEmail( $service ) );
                    } else {
                        Mail::to( $referral['Email'] )->send( new ReferralEmail( $service ) );
                    }
                }

                if ( $referral['Mobile'] != '' && $referral['SafeMobile'] != 0 ) {
                    if ( $referral['Nearest'] != '' ) {
                        Mail::to( $referral['Mobile'] . "@e2s.pcsms.com.au"  )->send( new ReferralPanelLawyerSms( $service ) );
                    } else {
                        Mail::to( $referral['Mobile'] . "@e2s.pcsms.com.au"  )->send( new ReferralSms( $service ) );
                    }
                }

                $log = new Log();
                $log::record('CREATE', 'referral', $service['RefNo'], $referral);

                return [ 'success' => 'success' , 'message' => 'Service saved.', 'data' => $response->SaveReferralResult ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage(), 'data' => $referral ];
        }
    }
    /**
     * Get answers from session
     * @return array $referral_answers referral answers
     */
    public function getAnswersFromSession()
    {
        $referral_answers = [];
        //eligibility
        $vls = explode(',', session('vls_id') );

        if ( !empty( $vls ) ) {
            foreach ( $vls as $eligibility ) {
                if (  $eligibility != '' ) {
                    $referral_answers[] =  [
                                            'Answer' => true,
                                            'QuestionId' => $eligibility,
                                            'RefNo' => 0 ,
                                            'ReferrelId' => 0
                                        ];
                }

            }
        }

        //questions
        $answers = sizeof( session('answers') );
        if( $answers > 0 )
        {
            foreach (session('answers') as $answer_id => $answer) {
                $referral_answers[] = [
                                        'Answer' => $answer,
                                        'QuestionId' => $answer_id,
                                        'RefNo' => 0 ,
                                        'ReferrelId' => 0
                                    ];
            }
        }
        return $referral_answers;
    }
    /**
     * Get services by catchment
     * @param  int   $ca_id    catchment id
     * @param  int   $mt_id    legal matter id
     * @return array $services services
     */
    public function getServicesByCatchmentId( $ca_id, $mt_id )
    {

        $info = [ 'CatchmentId' => $ca_id, 'MatterId' => $mt_id ];

        /**
         * PREVIOUS FUNCTION
         */
        $services = json_decode(
                                    $this->client
                                    ->ws_init( 'GetOrbitServicesWithMattersByCatchmentandMatterIdasJSON' )
                                    ->GetOrbitServicesWithMattersByCatchmentandMatterIdasJSON( $info )
                                    ->GetOrbitServicesWithMattersByCatchmentandMatterIdasJSONResult,
                                    true
                                );
        return $services;
    }
    /**
     * Get services by catchment and service provider
     * @param  int   $ca_id          catchment id
     * @param  int   $mt_id          legal matter id
     * @param  int   $sp_id          service provider id
     * @return array output_services services
     */
    public function getServicesByCatchmentIdAndSpId( $ca_id, $mt_id, $sp_id )
    {


        $info = [ 'CatchmentId' => $ca_id, 'MatterId' => $mt_id, 'ServiceProvderId' => $sp_id ];

        $services = json_decode(
            						$client
                                    ->ws_init( 'GetOrbitServicesWithMattersByCatchmentandMatterIdandSpIdasJSON' )
            						->GetOrbitServicesWithMattersByCatchmentandMatterIdandSpIdasJSON( $info )
            						->GetOrbitServicesWithMattersByCatchmentandMatterIdandSpIdasJSONResult,
            						true
        						);
        $output_services = [];

        foreach ( $services as $service ) {
            if ( !empty($service['ServiceActions']) ){
                $output_services[] = $service;
            }
        }
        return $output_services;
    }
    /**
     * Get vulnerabilities question by services
     * @param  int   $ca_id          catchment id
     * @param  int   $mt_id          legal matter id
     * @param  Sting $filter         Service Provider type filters
     * @return Array                 Vulnerabilities questions
     */
    public function getVulnerabilityByServices( $ca_id, $mt_id, $filter )
    {
        $vulnerability_obj = new Vulnerability();
        $vulnertability_questions = $vulnerability_obj->getAllVulnerabilityQuestions();

        $user = Auth::user();
        if( $user->sp_id > 0){
            $services = self::getServicesByCatchmentIdAndSpId( $ca_id, $mt_id, $user->sp_id );
        } else {
            $services = self::getServicesByCatchmentId( $ca_id, $mt_id );
        }
        // Filter Services
        $services = self::filterByServiceProviderType($services, $filter);
        $qu_id = [];
        $question_list = [];

        foreach ( $services as $service ) {
            foreach ( $service['ServiceMatters'] as $serviceMatter ) {
                foreach ( $serviceMatter['VulnerabilityMatterAnswers'] as $vulnerabilityMatterAnswer ) {
                    $qu_id[] = $vulnerabilityMatterAnswer['QuestionId'];
                }
            }
            foreach ( $service['ServiceVulAnswers'] as $serviceVulAnswer ) {
                $qu_id[] = $serviceVulAnswer['QuestionId'];
            }
        }

        foreach ( $vulnertability_questions as $vulnertability_question ) {
            if( in_array( $vulnertability_question['QuestionId'], $qu_id) ) {
                $question_list[] = $vulnertability_question;
            }
        }

        return ['vulnertability_questions' => $question_list, 'service_qty' => count( $services )];
    }
    /**
     * Return service matches according to catchment, legal matter, questions and service provider type
     * @param  int    $ca_id          catchment id
     * @param  int    $mt_id          legal matter id
     * @param  array  $vuln_list      vulnerabilities question
     * @param  Sting  $filter         service Provider type filters
     * @return array                  questions filtered
     */
    public function filterServices( $ca_id, $mt_id, $vuln_list, $filter )
    {

        $user = Auth::user();
        if( $user->sp_id > 0) {
            $services = self::getServicesByCatchmentIdAndSpId( $ca_id, $mt_id, $user->sp_id );
        } else {
            $services = self::getServicesByCatchmentId( $ca_id, $mt_id );
        }


        $service_match = false;
        $matches = [];

        //Define weights
        $vulnerability_w = config('referral.vulnerability');

        foreach ( $services as $service ) {
            //Global service match
            $service_match = self::matchVulnerability( $service['ServiceVulAnswers'], $vuln_list);

            //Each LM inside a service
            foreach ( $service['ServiceMatters'] as $legal_matter ) {
                if ( $legal_matter['MatterID'] == $mt_id ) {
                    // No answers and fit the global eligib.
                    if ( empty( $legal_matter['VulnerabilityMatterAnswers'] ) && $service_match ) {
                        // global eligibility match against vuln list
                        if( $vuln_list != '' && !empty( $service['ServiceVulAnswers'] ) ) {
                            $service['sort']['eligibility'][] = $legal_matter;
                            $service['sort']['weight'] =
                            ( isset($service['sort']['weight']) ?  $service['sort']['weight'] + $vulnerability_w : $vulnerability_w);
                        }
                        $matches[ $service['ServiceId'] ] = $service;
                    }
                    // Answered/checked eligibility on specific LM and match vuln list.
                    elseif ( !empty( $legal_matter['VulnerabilityMatterAnswers'] ) && $vuln_list != ''
                        && self::matchVulnerability( $legal_matter['VulnerabilityMatterAnswers'], $vuln_list) ) {
                        $service['sort']['eligibility'][] = $legal_matter;
                        $service['sort']['weight'] =
                        ( isset($service['sort']['weight']) ?  $service['sort']['weight'] + $vulnerability_w : $vulnerability_w);

                        $matches[ $service['ServiceId'] ] = $service;
                    }
                }
            }
        }
        //Get list of questions of a legal matter
        $question_list = self::getMatterQuestions( $matches, $mt_id, $filter );
        // Filter current matches
        $matches = self::filterByServiceProviderType( $matches, $filter );
        session(['matches' => $matches ]);
        return $question_list;
    }
    /**
     * Filter Services by VLA, CLC, Non-legal, Private Practitioner
     * @param  Object $services Services to filter
     * @param  String $filter   Filter to apply
     * @return Object           Services filtered
     */
    public function filterByServiceProviderType($services, $filter)
    {
        $filters=explode(',', $filter);
        foreach ( $services as $key => $service ) {
            if ( !in_array($service["ServiceProviderTypeId"], $filters) ) {
                unset($services[$key]);
            }
        }
        return $services;
    }
    /**
     * Match vulnerabilities
     * @param  Object $vulnerability    vulnerability
     * @param  array  $client_vuln_list vulnerabilities list
     * @return array  $match            vulnerabilities matched
     */
    public function matchVulnerability( $vulnerability, $client_vuln_list )
    {
        $match = false;
        $service_vuln_list = [];
        $service_vuln_list = array_column( $vulnerability, "QuestionId" );

        $client_vuln_list = explode( ",", $client_vuln_list );
        if( empty( $vulnerability ) ) {
            $match = true;
        } else {
            foreach ( $service_vuln_list as $vu_id ) {
                if ( in_array( $vu_id, $client_vuln_list ) ) {
                    $match = true;
                }
            }
        }
        return $match;
    }
    /**
     * Get matter questions
     * @param  array  $services     services
     * @param  int    $mt_id        legal matter id
     * @param  String $filter       service provider type
     * @return array  question_list matter questions
     */
    public function getMatterQuestions( $services, $mt_id, $filter)
    {
        $question_list = [];
        $filters=explode(',', $filter);
        foreach ( $services as $service ) {
            // Before get the questions, filter the services
            if ( in_array($service["ServiceProviderTypeId"], $filters) ) {
                if( !empty( $service['ServiceMatters'] ) )  {

                    $matter_pos = array_search( $mt_id,  array_column( $service['ServiceMatters'], 'MatterID' ) );
                    $matter_questions = $service['ServiceMatters'][ $matter_pos ]['MatterQuestions'];
                    $matter_answers   = $service['ServiceMatters'][ $matter_pos ]['MatterAnswers'];
                    foreach ( $matter_questions as $question ) {
                        $qu_id = $question['QuestionId'];
                        $current_answers = self::getMultipleAnswers( $qu_id, $matter_answers );
                        if ( !empty($current_answers) ) {
                            if ( !isset( $question_list[ $qu_id ]['prop'] ) ) {
                                $question_list[ $qu_id ]['prop'] = [
                                                                    'Operator'      => $question['Operator'],
                                                                    'QuestionName'  => $question['QuestionName'] ,
                                                                    'QuestionTypeName'  => $question['QuestionTypeName']
                                                                    ] ;
                            }
                            if ( $question['QuestionTypeName'] == 'multiple' ) {
                                if ( !isset( $question_list[ $qu_id ]['prop']['QuestionValue'] ) ) {
                                    $question_list[ $qu_id ]['prop']['QuestionValue'] = $current_answers ;
                                } else {
                                    foreach ( $current_answers as $answer ) {
                                        array_push( $question_list[ $qu_id ]['prop']['QuestionValue'], trim( $answer ) );
                                    }
                                }
                            }
                            $question_list[ $qu_id ]['services'][] = $service['ServiceId'];
                        }
                    }
                }
            }
        }
        return $question_list;
    }
    /**
     * Get multiple answsers
     * @param  int    $qu_id          question id
     * @param  array  $matter_answers legal matter answers
     * @return array  $answers        answers
     */
    public function getMultipleAnswers( $qu_id, $matter_answers )
    {
        $answers = [];
        foreach ( $matter_answers as $answer ) {
            if( $answer['QuestionId'] == $qu_id && $answer['QuestionValue'] != ' ' ) {
                $answers = explode( ',', $answer['QuestionValue'] );
            }
        }
        return $answers;
    }
    /**
     * Filter by questions
     * @param  array  $answers answers
     * @param  int    $mt_id   legal matter id
     * @return array  $matches questions
     */
    public function filterByQuestions( $answers, $mt_id )
    {
        $services = session('matches');
        $matches = [];
        foreach ( $services as $service ) {
            if ( empty( $service['ServiceMatters'] ) ) { //not set answers on legal matter inside service, match by default

                $matches[ $service['ServiceId'] ] = $service;
            }

            foreach ( $service['ServiceMatters'] as $legal_matter ) {
                $match_sa = self::matchServiceAnswersWithAnswers( $answers, $legal_matter['CommonMatterAnswers'] );
                if ( $match_sa['match'] && $legal_matter['MatterID'] == $mt_id ) {
                    if ( !empty( $legal_matter['CommonMatterAnswers'] ) ) {
                        $service['sort']['questions'][] = $legal_matter;
                        $service['sort']['weight'] = ( isset($service['sort']['weight']) ?  $service['sort']['weight'] +  $match_sa['weight']  :  $match_sa['weight'] );
                    }
                    $matches[ $service['ServiceId'] ] = $service;
                }
            }
        }

        return $matches;
    }
    /**
     * Match service with anwers
     * @param  array  $answers        answers
     * @param  array  $serviceAnswers service answers
     * @return array                  match and weight
     */
    public function matchServiceAnswersWithAnswers( $answers, $serviceAnswers )
    {
        $weight = 0; // For questions that are not answered is false
        $match  = true;
        //Define weights
        $question_w = config('referral.question');
        foreach ( $serviceAnswers as $sericeAnswer ) { //each question by matter
            $sva_id = $sericeAnswer['QuestionId'];
            if ( isset( $answers[ $sva_id ] ) ) { //was the question answered?
                $sericeAnswer['answer'] = $answers[ $sva_id ];
            } else {
                $sericeAnswer['answer'] = " ";
            }

            if ( !self::compareQuestionAnswer( $sericeAnswer ) ) { // not apply for this service
                $match = false;
            } elseif ( $sericeAnswer['QuestionValue'] != ' ' && self::compareQuestionAnswer( $sericeAnswer ) ) {
                $weight += $question_w;
            }
        }
        return [ 'match' => $match, 'weight' => $weight ];
    }
    /**
     * Compare questions answer
     * @param  array  $args answers
     * @return bool         answer comparison
     */
    public function compareQuestionAnswer( $args )
    {
        switch ( $args['Operator'] ) {
            case '>':
                return ( $args['answer'] > $args["QuestionValue"] );
                break;
            case '>=':
                return ( $args['answer'] >= $args["QuestionValue"] );
                break;
            case '<':
                return ( $args['answer'] < $args["QuestionValue"] );
                break;
            case '<=':
                return ( $args['answer'] <= $args["QuestionValue"] );
                break;
            case '=':
                return ( $args['answer'] == $args["QuestionValue"] );
                break;
            case 'in':
                $options = explode( ',', $args['QuestionValue']);
                foreach ($options as $answer) {
                    if( trim( strtolower($answer) ) == trim( strtolower($args['answer']) ) )
                    {
                        return true;
                    }
                }
                return false;
                break;
            default:
                # Check empty values in question because the answer was not answered
                # Does not matter the answer to this question is always true
                return ( $args["answer"] == " " || $args["QuestionValue"] == " " );
                break;
        }
    }
    /**
     * Sort matches
     * @param  array $services services
     * @return array $matches  services sorted
     */
    public function sortMatches( $services )
    {
        //Define weights
        $catcment_w = config('referral.catchment');
        $legal_help_w = config('referral.legal_help');
        $matches = [];
        foreach ( $services as $key => $service )
        {
            // is LH
            $sp_type_name =  $service['ServiceProviderTypeName'];
            if ( $sp_type_name == 'Legal Help' ) { // is Legal Help
                $service['sort']['is_lh']  = true;
                $service['sort']['weight'] = ( isset($service['sort']['weight']) ?  $service['sort']['weight'] + $legal_help_w : $legal_help_w);
            }

            // Catchment
            $catchments = $service['ServiceCatchments'];
            foreach ( $catchments as $catchment ) {
                if ( $catchment['CatchmentPostcode'] != 0 ) {
                    $service['sort']['weight'] =
                    ( isset($service['sort']['weight']) ? $service['sort']['weight'] + $catcment_w : $catcment_w);
                }
            }
            $matches[$key] = $service;
        }

        array_multisort(
            array_map(function($element) {
                if ( isset( $element['sort']['weight'] ) ) {
                    return $element['sort']['weight'];
                } else {
                    return 0;
                }
            }, $matches)
        , SORT_DESC, $matches);

        return $matches;
    }

}
