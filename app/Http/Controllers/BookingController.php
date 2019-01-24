<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use File;
use App\Booking;
use App\BookingDocument;
use App\BookingEngine;
use App\SentSms;
use App\Service;
use App\ServiceProvider;
use App\ServiceBooking;
use DateTime;
use Auth;

/**
 * Booking Controller.
 * Controller for the booking functionalities
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  Controller
 */
class BookingController extends Controller
{
    /**
     * Booking contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of booking
     * @return view booking information
     */
    public function index()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        return view("booking.index");
    }
    /**
     * Display a listing of the next booking
     * @return view next bookiig information
     */
    public function nextBookings()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        return view("booking.next_bookings");
    }
    /**
     * Display a listing of booking by service provider
     * @return view booking by service provider information
     */
    public function byServiceProvider()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        return view("booking.by_service_provider", compact( 'service_providers' ));
    }
    /**
     * Display a listing of booking by service provider
     * @return view booking by service provider information
     */
    public function byServiceProvider2()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        return view("booking.by_service_provider2", compact( 'service_providers' ));
    }
    /**
     * Display a specific booking
     * @param  int $bk_id booking id
     * @return view single booking information page
     */
    public function show( $bk_id )
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        return view( "booking.show", compact( 'service_providers' ) );
    }
     /**
     * Show the form for creating a new service
     * @return view service creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        return view("booking.create", compact( 'service_providers' ) );
    }
    /**
     * Remove the specified booking from data base.
     * @param  int $bo_id booking id
     * @return mixed booking listing page with success/error message
     */
    public function destroy( $bo_id )
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        $booking_obj = new Booking();
        $response = $booking_obj->deleteBooking( $bo_id );

        return redirect('/booking/by_service_provider')->with($response['success'], $response['message']);
    }
    /**
     * Get bookable services by date
     * @param  int      $year  year
     * @param  int      $month month
     * @param  int      $sv_id service id
     * @return array           list of available services.
     */
    public function getServiceDatesByDate( $year, $month, $sv_id )
    {
        $init_year = $finish_year = $year;
        $finish_month = $month + 1;
        $init_date   = $init_year . "-" . $month . "-01";

        if( $month > 11 )
        {
            $finish_year += 1;
            $finish_month = "01";
        }
        $finish_date = $finish_year . "-" . $finish_month . "-01";

        $booking_obj = new Booking();
        return $booking_obj->getBookableServiesByDayWithTime( $sv_id, $init_date, $finish_date);
    }
    /**
     * Store a newly or updated booking in the data base
     * @return mixed booking listing page with success/error message
     */
    public function store()
    {
        //dd(request());
        $booking_obj = new Booking();
        $booking_engine_obj = new BookingEngine();
        $request_type = request('request_type');
        if( $request_type == 0 ) //Direct booking - Booking Bug integration
        {
            // Validate form
            $validation = $this->validateBookingData(request()->all());
            if ($validation->fails()) {
                return back()->withInput()
                            ->withErrors($validation->errors());
            }

            $serviceId = explode( '-', request('ServiceId') );

            $service_booking_id = $serviceId[0];
            // Get the booking extra information
            $extra_data = [
                'Safe'      => (is_null( request('Safe') ) ? 'true' : request('Safe') ),
                'CIRNumber' => (is_null( request('CIRNumber') ) ? '' : request('CIRNumber') ),
                'IsComplex' => (is_null( request('IsComplex') ) ? 0 : request('IsComplex') ),
                'IsSafeSMS' => (is_null( request('phonepermission') ) ? 0 : ( request('phonepermission') == 'Yes' ? 1 : 0 ) ),//phonepermission
                'IsSafeCall' => (is_null( request('phoneCallPermission') ) ? 0 : ( request('phoneCallPermission') == 'Yes' ? 1 : 0 ) ),//phoneCallPermission
                'IsSafeLeaveMessage'  => (is_null( request('phoneMessagePermission') ) ? 0 : ( request('phoneMessagePermission') == 'Yes' ? 1 : 0 ) ),//phoneMessagePermission
                'ContactInstructions' => (is_null( request('reContact') ) ? '' : request('reContact') ),//reContact
                'RemindNow' => (is_null( request('RemindNow') ) ? 0 : ( request('RemindNow') == 'Yes' ? 1 : 0 ) ),//phonepermission
                'CreatedBy' => (is_null( request('created_by') ) ? '':request('created_by')),
                'OfficeId'  => \App\Http\helpers::getUSerServiceProviderId()  // The user office (sp)
            ];
            // Create the booking object
            $booking = [
                'comment'   => request('Desc'),
                'contact'   => (is_null( request('phone') ) ? '' : request('phone') ),
                'first_name' =>  request('firstName'),
                'last_name' => request('lastName'),
                'start_hour'  =>  request('start_hour'),
                'time_length' => request('time_length'),
                'resource_id' => request('resource_id'),
                'date' => request('booking-date'),
                'hour' => request('text'),
                'is_interpreter' => (is_null( request('Language') ) ? 0 : 1 ),
                'int_language' => (is_null( request('Language') ) ? '' : request('Language') ),
                'service_id' => $service_booking_id,
                'CIRNumber' => (is_null( request('CIRNumber') ) ? '' : request('CIRNumber') ), // Put it here to be retrieved easily when send the email
                'RemindNow' => (is_null( request('RemindNow') ) ? 0 : ( request('RemindNow') == 'Yes' ? 1 : 0 ) ),// Put it here to be retrieved easily when send the reminder
                'data' => json_encode($extra_data)
            ];

            // Get the Service provider
            $sp_id = request('service_provider_id');
            $service_providers_obj   = new ServiceProvider();
            $service_provider_result = $service_providers_obj->getServiceProviderByID( $sp_id );
            $service_provider = json_decode( $service_provider_result['data'] )[0];
            //Store booking
            $reservation = $booking_engine_obj->storeBooking($booking);
            // Notify booking
            $service_name = request('ServiceName');
            $booking["booking_no"] = $reservation;
            $booking_obj->notifyBooking( $booking, $service_provider, $service_name );

            //Upload attached files

            $main_file['files'] = request('files');
            $other_files = request('attachments');

            if( !empty($main_file['files']) && !empty($other_files) )
            {
                $files = array_merge([$main_file], $other_files);
            }
            else if( !empty($main_file['files']) )
            {
                $files[] = $main_file;
            }
            else if( !empty($other_files) )
            {
                $files = $other_files;
            }

            if( !empty( $files ) )
            {
                $booking_document = new BookingDocument();
                foreach ($files as $file)
                {
                    $fileName = $file['files']->getClientOriginalName();
                    $file['files']->move( public_path('booking_docs') . '/' . $reservation , $fileName );
                    $booking_document->saveBookingDocument( $fileName , $reservation );
                }
            }

            return redirect('/booking/by_service_provider')->with('success', 'Booking saved.');
        } else {
            $response = $booking_obj->requestBooking( request()->all() );
            if ( $response ) {
                return redirect('/booking/by_service_provider')->with('success', 'e-Referral sent.');
            }
            else {
                return redirect('/booking/by_service_provider')->with('error', 'Email not set in service, please contact an administrator.');
            }
        }
    }
    /**
     * Validate booking data to be stored
     *
     * @param Request $request
     * @return void
     */
    private function validateBookingData($request)
    {
        $rules = [
            'files'               => 'nullable|mimes:pdf,png,jpeg,bmp,jpg,doc,docx,xls,xlsx,msg|max:4096',
            'attachments.*.files' => 'nullable|mimes:pdf,png,jpeg,bmp,jpg,doc,docx,xls,xlsx,msg|max:4096',
            'service_provider_id' => 'required',
            'ServiceId'           => 'required',
            'request_type'        => 'required',
            'start_hour'          => 'required',
            'firstName'           => 'required',
            'lastName'            => 'required',
            'Desc'                => 'required',
            'booking-date'        => 'required'

        ];
        $customMessages = [
            'required' => 'The :attribute field is required.',
            'mimes'    => 'Failed to upload file(s), Check format. Formats accepted: pdf,png,jpeg,bmp,jpg,doc,docx,xls,xlsx,msg.',
            'max'      => 'Failed to upload file(s), Check size. Max size: 4MB'
        ];
        $validator = Validator::make($request,
            $rules,
            $customMessages
        );
        return $validator;
    }

    /**
     * List all booking for the next month
     * @return array list of all booking
     */
    public function list()
    {
        $booking_obj = new Booking();
        return $booking_obj->getAllBookingsNextMonthCalendar( date("Y-m-d"), date("Y-m-d", strtotime("+1 month")) );
    }
    /**
     * List all booking by month
     * @return array list of all booking filtered by month
     */
    public function listCalendar()
    {
        $booking_obj = new Booking();
        return $booking_obj->getAllBookingsPerMonthCalendar( request('start'), request('end') ) ;
    }
    /**
     * List all booking by month and service provider
     * @return array list of all booking by month and service provider
     */
    public function listCalendarBySp()
    {
        $booking_obj = new Booking();
        return $booking_obj->getAllBookingsPerMonthCalendar( request('start'), request('end') , request('sp_id') ) ;
    }
    /**
     * List all booking by user
     * @return array list of all booking by user
     */
    public function listCalendarByUser()
    {
        $booking_obj = new Booking();
        $bookings = $booking_obj->getBookingsByUser() ;
        return array( 'data' => $bookings );
    }
    /**
     * Get calendar viw
     * @return view calendar
     */
    public function calendar()
    {
        return view("booking.calendar");
    }
    /**
     * Store a updated booking in the data base
     * @return Object $result booking update result
     */
    public function updateBooking2()
    {
        $booking_obj = new Booking();
        $result = $booking_obj->updateBooking( request()->all() ) ;

        return $result;
    }
    /**
     * Update The Booking element
     *
     * @param Request $request
     * @return void
     */
    public function updateBooking(Request $request)
    {
        $booking = $this->getBookingData($request);
        $validation = $this->validateBooking($booking);
        if(!$validation->fails()) {
            $booking_engine_obj = new BookingEngine();
            $result = $booking_engine_obj->updateBooking($booking);
            return $result->id;
        } else {
            return response()->json(['error'=>$validation->errors()]);
        }

    }

    /**
     * Get the booking data from the request.
     *
     * @param array $request
     * @return array
     */
    protected function getBookingData($request)
    {
        // Create the booking object
        $extra_data = '';
        if (isset($request['data'])) {
            $extra_data =   $request['data'];
            $extra_data['CIRNumber'] = (is_null( $request['data']['CIRNumber'] ) ? '' : $request['data']['CIRNumber'] );
            $extra_data['ContactInstructions'] =  (is_null( $request['data']['ContactInstructions'] ) ? '' : $request['data']['ContactInstructions'] );
        }
        $booking = [
            'date'              => (is_null($request['date']) ? null : $request['date']),
            'day'               => $request['day'],
            'start_hour'        => (is_null($request['start_hour']) ? null : $request['start_hour']),
            'time_length'       => $request['time_length'],
            'comment'           => (is_null($request['comment']) ? null : $request['comment']),
            'is_interpreter'    => (is_null($request['is_interpreter']) ? '' : $request['is_interpreter']),
            'int_language'      => (is_null($request['int_language']) ? '' : $request['int_language']),
            'data'              => json_encode($extra_data),
            'booking_status_id' => (is_null($request['booking_status_id']) ? null : $request['booking_status_id']),
            'first_name'        => (is_null($request['client']['first_name']) ? null : $request['client']['first_name']),
            'last_name'         => (is_null($request['client']['last_name']) ? null : $request['client']['last_name']),
            'contact'           => (is_null($request['client']['contact']) ? null : $request['client']['contact']),
            'client_id'         => (is_null($request['client_id']) ? null : $request['client_id']),
            'booking_id'        => $request['id'],
        ];

        return $booking;
    }

    /**
     * Validate Booking data when update
     *
     * @param array $booking
     * @return void
     */
    protected function validateBooking($booking)
    {
        $rules = [
            'booking_id'        => 'required',
            'start_hour'        => 'required',
            'first_name'        => 'required',
            'last_name'         => 'required',
            'comment'           => 'required',
            'date'              => 'required',
            'start_hour'        => 'required',
            'client_id'         => 'required',
            'booking_status_id' => 'required'
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $validation = Validator::make($booking, $rules, $customMessages);
        return $validation;
    }

    /**
     * Store a updated booking details in the data base
     * @return Object $result booking details update result
     */
    public function updateBookingDetails()
    {
        $booking = request('booking');

        $booking_obj = new Booking();
        $result = $booking_obj->updateBookingDetails( json_decode($booking) ) ;

        return $result;
    }
    /**
     * Send booking SMS reminder
     * @return Object $result SMS reminder result
     */
    public function sendSmsReminder()
    {
        //$reminder = request('reminder');
        $booking = json_decode(request('booking'));
        //$reminder['template']; //Custom Template
        $sent_sms_obj = new SentSms();
        $result = $sent_sms_obj->sendReminder( $booking );
        return $result;
    }
    /**
     * List all Legal Help bookings
     * @return array list of all Legal Help bookings
     */
    public function listLegalHelpBookings()
    {

        $booking_engine_obj = new BookingEngine();
        $service_booking_obj = new ServiceBooking();
        $service_obj = new Service();
        $sent_sms_obj = new SentSms();
        $replace = ["/Date(", ")/"];
        $date_array = [];
        $bookings = $booking_engine_obj->legalHelpBookings();
        $service_bookings = $service_booking_obj->getAllServiceBookings();
        foreach ($bookings as $key => $booking) {
            foreach ($service_bookings as $service_booking) {
                if($service_booking['BookingServiceId']== $booking->service_id)
                {
                    $result = $service_obj->getServiceByID($service_booking['ServiceId']);
                    $service = json_decode( $result['data'] )[0];
                    $bookings[$key]->serviceProviderName = $service->ServiceProviderName;
                    $bookings[$key]->serviceName = $service->ServiceName;
                    // Get SMS
                    $date = new DateTime();
                    $sms_messages = $sent_sms_obj->getSentSMSByBookingRefID($booking->id);
                    foreach ($sms_messages as $sms_message) {
                        $sms_date = str_replace($replace,"", $sms_message['SentDate']);
                        $date->setTimestamp(intval(substr($sms_date, 0, 10)));
                        $date_formatted = $date->format('d/m/Y');
                        $date_array[] = $date_formatted;
                    }
                    $bookings[$key]->sms_sent = implode (", ", $date_array);
                    // Set time
                    $valute_in_hours = intval($bookings[$key]->start_hour)/60;
                    $hour = sprintf("%02d", floor($valute_in_hours) );
                    $minute = sprintf("%02d", round(fmod($valute_in_hours, 1) * 60));
                    $bookings[$key]->time = "$hour:$minute";
                    break;
                }

            }
            $date_array= [];
        }
        return [ 'data' => $bookings];

        /*
        $booking_obj = new Booking();
        return array( 'data' => $booking_obj->legalHelpBookings() );*/
    }
    /**
     * Display Legal Help booking
     * @return view legal help booking information
     */
    public function legalHelp()
    {
        return view("booking.legal_help");
    }
}
