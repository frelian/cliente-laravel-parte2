<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    /**
     * Listar los clientes del vendedor conectado
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = Session::get('token');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];

        // Hago la peticion al servidor Passport
        $client = new Client();

        // Valido que exista la variable global
        $url_server = env('SERVER_API_URL') ? env('SERVER_API_URL') : 'http://127.0.0.1:8000/api/';


        // api/auth/logout
        $response = $client->request('POST', $url_server . 'clients', [
            'headers' => $headers
        ]);

        $body = $response->getBody();
        $content = $body->getContents();
        $response_json = json_decode($content);

        $response = [
            'result' => true,
            'message' => $response_json
        ];

        return view('clients.index')
            ->with('clientsPaginate', $response_json->clients)
            ->with('clientsData', $response_json->clients->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSale($idclient)
    {
        $token = Session::get('token');

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ]
        ]);

        // Valido que exista la variable global
        $url_server = env('SERVER_API_URL') ? env('SERVER_API_URL') : 'http://127.0.0.1:8000';

        // api/client/products/{idclient}
        $response = $client->post($url_server . 'client/products/' . $idclient,
            ['body' => json_encode(
                [
                    'idcliente' => $idclient,
                ]
            )]
        );


        $body = $response->getBody();
        $content = $body->getContents();
        $response_json = json_decode($content);

        $response = [
            'result' => true,
            'message' => $response_json
        ];

        return view('clients.products')
            ->with('client', $response_json->client)
            ->with('productsPaginate', $response_json->products)
            ->with('productsData', $response_json->products->data);
    }

    public function storeProducts(Request $request)
    {
        $token = Session::get('token');

        $data = $request->all();
        $data = $data['arrayProductos'];

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ]
        ]);

        // Valido que exista la variable global
        $url_server = env('SERVER_API_URL') ? env('SERVER_API_URL') : 'http://127.0.0.1:8000/api/';

        // client/products/store
        $response = $client->post($url_server . 'store/sale',
            ['body' => json_encode(
                [
                    'arraydata' => $data,
                ]
            )]
        );

        $body = $response->getBody();
        $content = $body->getContents();
        $response_json = json_decode($content);

        $response = [
            'result' => $response_json->success,
            'message' => $response_json
        ];

        return response()->json($response, 200);
    }
}
