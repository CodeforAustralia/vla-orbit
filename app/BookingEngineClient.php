<?php
namespace App;

/**
 * Orbit Soap Client for all Web Services
 *
 * @author Christian Arevalo
 * @version 1.0.0
 */
Class BookingEngineClient
{
    protected $client;

    function __construct()
    {
        $this->client = (new \App\Repositories\BookingEngineAPI);
    }
}