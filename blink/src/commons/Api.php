<?php

namespace Blink;
defined('ABSPATH') or die;

/**
 * Class Api
 *
 * @package Blink
 */
class Api
{

    private const jsonHeader = "Content-type: application/json";

    // Endpoints
    private const loginEndpoint = 'users/login/';
    private const postPublicKeyEndpoint = "merchants/credentials/";

    /**
     * Login the user and return the a bearer token.
     *
     * @param string $email
     * @param string $password
     *
     * @return bool|string - The login token or False in the case of error.
     */
    public static function login($email, $password)
    {
        $data = json_encode(array(
            'email' => $email,
            'password' => $password,
        ));
        $response = self::post(self::loginEndpoint, $data);
        // return false if the call has failed
        if ($response === FALSE) {
            return FALSE;
        }
        $response = json_decode($response);
        // return login token
        if (!empty($response->error->code)) {
            return FALSE;
        }
        return $response->key;
    }

    public static function postPublicKey($publicKey, $signedPubKey, $loginToken)
    {
        $data = json_encode(array(
            'publicKey' => $publicKey,
            'signature' => $signedPubKey,

        ));
        $response = self::post(self::postPublicKeyEndpoint, $data, $loginToken);
        // return false if the call has failed
        if ($response === FALSE) {
            return FALSE;
        }
        $response = json_decode($response);
        return $response->merchant;
    }

    //-------- Helper functions --------

    /**
     * Build the bearer Authorization header
     *
     * @param string $token
     *
     * @return string
     */
    private static function buildAuthHeader($token)
    {
        return "Authorization: Bearer " . $token;
    }


    /**
     * Post json encoded data to a endpoint.
     *
     * @param string      $endpoint   - Api endpoint where to post data
     * @param string      $data       - Data to be submitted in the post request json encoded
     * @param string|null $loginToken - Optional token for the Authorization header
     *
     * @return bool
     */
    private static function post($endpoint, $data, $loginToken = null)
    {
        $curl = curl_init(Constants::getApiUrl() . $endpoint);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headerArray = array(self::jsonHeader, 'Content-Length: ' . strlen($data));
        if ($loginToken != null) {
            array_push($headerArray, self::buildAuthHeader($loginToken));
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    private static function get($endpoint, $paramArray = null) {
        $curl_url =  Constants::getApiUrl() . $endpoint;
        if(!empty($paramArray)) {
            $curl_url = $curl_url . "?" . http_build_query($paramArray);
        }
        $curl = curl_init($curl_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
