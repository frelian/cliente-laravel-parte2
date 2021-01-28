<?php

namespace App\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
// use Session;

class Helpers
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function isAuthenticated($token = null)
    {

        $token = isset($token) ? $token : Session::get('token');
        // $token = \Cache::get('token');

        if ($token) {
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ];

            // Hago la peticion al servidor Passport
            $client = new Client();

            // Valido que exista la variable global
            $url_server = env('SERVER_API_URL') ? env('SERVER_API_URL') : 'http://127.0.0.1:8000/api/';

            try {

                // api/auth/logout
                $response = $client->request('GET', $url_server . 'auth/user', [
                    'headers' => $headers
                ]);

                $body = $response->getBody();
                $content = $body->getContents();
                $response_json = json_decode($content);

                $response = [
                    'result' => true,
                    'message' => $response_json
                ];
                return $response;

            } catch (ClientException $e) {

                $str = strpos($e->getMessage(), '{');
                $json = substr($e->getMessage(), $str);
                $json = json_decode($json);
                $message = $json->message;
                $response = [
                    'result' => false,
                    'message' => $message
                ];

                return $response;
            }
        }

        $response = [
            'result' => false,
            'message' => "Su sesión ha caducado"
        ];

        return $response;
    }
}
