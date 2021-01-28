<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Request;
// use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'loginForm');
    }

    public function login() {

        $request  = Request::all();
        $email    = $request['email'];
        $password = $request['password'];

        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

        // Valido que exista la variable global
        $url_server = env('SERVER_API_URL') ? env('SERVER_API_URL') : 'http://127.0.0.1:8000';

        try {
            $response = $client->post($url_server . 'login',
                ['body' => json_encode(
                    [
                        'email'    => $email,
                        'password' => $password
                    ]
                )]
            );

            $body = $response->getBody();
            $content = $body->getContents();

            $response_json = json_decode($content);

            Session::put('token', $response_json->access_token);
            // \Cache::put('token', $response_json->token->token, 10);

            return redirect()->route('clients');

        } catch (ClientException $e) {

            $str = strpos($e->getMessage(), '{');
            $json = substr($e->getMessage(), $str);
            $json = json_decode($json);
            $result = $json->message;

            return Redirect::back()->withErrors([$result]);
        }
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function logout($token = null)
    {
        $token = isset($token) ? $token : Session::get('token');

        if ($token) {

            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ];

            // Hago la peticion al servidor Passport
            $client = new Client();

            // Valido que exista la variable global
            $url_server = env('SERVER_API_URL') ? env('SERVER_API_URL') : 'http://127.0.0.1:8000/api/';

            // Valido la sesion con un Try por cerrada la sesion, el usuario vuelve a ir a logout
            try {

                // api/auth/logout
                $response = $client->request('GET', $url_server . 'auth/logout', [
                    'headers' => $headers
                ]);

                $body = $response->getBody();
                $content = $body->getContents();
                $response_json = json_decode($content);

                Session::forget('token');
                Session::flush();

                return redirect()->route('login.form');
            } catch (ClientException $e) {

                $str = strpos($e->getMessage(), '{');
                $json = substr($e->getMessage(), $str);
                $json = json_decode($json);
                $message = $json->message;
                $response = [
                    'result' => false,
                    'message' => $message
                ];

                return redirect()->route('login.form');
            }
        } else {
            return redirect()->route('login.form');
        }
    }
}
