<?php

namespace App\Http\Controllers;
use GuzzleHttp;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class AuthController extends Controller
{
    public function login(Request $request) {
        $client = new GuzzleHttp\Client;;

        try {
            $response = $client->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->username,
                    'password' => $request->password,
                ]
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $exception) {
            if ($exception->getCode() == 400) {
                return response()->json('Invalid request. Please enter a username and a password', $exception->getCode());
            } else if ($exception->getCode() == 401) {
                return response()->json('Your credentials are incorrect. Please try again', $exception->getCode());
            }

            return response()->json('Something went wrong on the server', $exception->getCode());
        }


    }
}
