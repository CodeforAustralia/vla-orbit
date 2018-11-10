<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Exception;

/**
 * Set a Repository for the Booking Models.
 *
 * @author Christian Arevalo, Sebastian Currea
 * @version 1.0.0
 */

const LOGIN_URL = '/api/auth/login';
const LOGOUT_URL = '/api/auth/logout';
class BookingEngineAPI
{

    private $client, $email, $password, $remember_me;
    /**
     * Constructor. Initialize client and basic auth
     */
    function __construct()
    {
        $this->client = new Client(['base_uri' => env( 'BOOKING_ENGINE_BASE_URL' )]);
        $this->email        = env( 'BOOKING_ENGINE_EMAIL' );
        $this->password     = env( 'BOOKING_ENGINE_PASSWORD' );
        $this->remember_me  = env( 'BOOKING_ENGINE_REMEMBER_ME' );
    }

    /**
     * Post request to API
     *
     * @param array $form_params Form Information
     * @param array $tokens Tokens returned on login
     * @param string $url Url to access through the method
     * @return array
     */
    public function post($form_params, $tokens, $url)
    {
            $headers = [
                'content-type' => 'application/x-www-form-urlencoded',
                'Authorization' => $tokens['token_type'] . ' ' . $tokens['access_token'],
            ];
            $response = $this->client->post(
                                        $url,
                                        [
                                            'headers' => $headers,
                                            'form_params' => $form_params
                                        ]
                                    )->getBody();
            $data = json_decode($response);
            return $data;
    }
    /**
     * Patch request to API
     *
     * @param array $form_params Form Information
     * @param array $tokens Tokens returned on login
     * @param string $url Url to access through the method
     * @return void
     */
    public function patch($form_params, $tokens, $url)
    {
        $headers = [
            'content-type' => 'application/x-www-form-urlencoded',
            'Authorization' => $tokens['token_type'] . ' ' . $tokens['access_token'],
        ];
        $response = $this->client->patch(
                                        $url,
                                        [
                                            'headers' => $headers,
                                            'form_params' => $form_params
                                        ]
                                        )->getBody();
        $data = json_decode($response);
        return $data;

    }

    /**
     * Get request to API
     *
     * @param array $tokens Tokens returned on login
     * @param string $url Url to access through the method
     * @return array
     */
    public function get($tokens, $url)
    {
        try{
            $headers = [
                'content-type' => 'application/x-www-form-urlencoded',
                'Authorization' => $tokens['token_type'] . ' ' . $tokens['access_token'],
            ];
            $response = $this->client->get(
                                        $url,
                                        [
                                            'headers' => $headers
                                        ]
                                    )->getBody();
            $data = json_decode($response);
            return $data;
        }catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Login to get tokens
     *
     * @return array tokens
     */
    public function login()
    {
        $response = $this->client->post(
                                        LOGIN_URL,
                                        [
                                            RequestOptions::JSON => [
                                                                        "email" => $this->email,
                                                                        "password" => $this->password,
                                                                        "remember_me" => $this->remember_me
                                                                    ]
                                        ]
                                    )->getBody();
        $data = json_decode($response);
        return  [
                    'token_type' => $data->token_type,
                    'access_token' => $data->access_token
                ];

    }

    /**
     * Logout destroying current token/session
     *
     * @return array
     */
    public function logout()
    {
        try{
            $headers = [
                'content-type' => 'application/x-www-form-urlencoded',
                'Authorization' => $tokens['token_type'] . ' ' . $tokens['access_token'],
            ];
            $response = $this->client->get(
                                            LOGOUT_URL,
                                            [
                                                'headers' => $headers
                                            ]
                                        )->getBody();
            $data = json_decode($response);
            return  $data;
        }catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}