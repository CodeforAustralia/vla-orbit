<?php
namespace App;

/**
 * Orbit Soap Client for all Web Services
 *
 * @author Christian Arevalo
 * @version 1.2.0
 */
Class OrbitSoap
{
    protected $client;

    function __construct()
    {
	    $this->client = (new \App\Repositories\VlaSoap);
    }
}