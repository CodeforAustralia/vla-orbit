<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;

class BookingController extends Controller
{
    
    public function index()
    {
        $booking_obj = new Booking();
        //dd( $booking_obj->getBookableServiesByDayWithTime('79268', '2017-07-01', '2017-07-09') );
        return view("booking.index");
    }

    public function show()
    {
        return view("booking.show");
    }
    
    public function create()
    {
        return view("booking.create");
    }
}
