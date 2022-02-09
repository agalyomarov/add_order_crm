<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    public function index()
    {
        return view('order');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $client = new Client([
            'base_uri' => 'https://superposuda.retailcrm.ru',
        ]);

        $fio = explode(' ', $data['fio']);

        $response = $client->request(
            'GET',
            'api/v5/store/products',
            [

                'query' => ['apiKey' => 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb'],
                'verify' => false
            ]
        );
        $products = $response->getBody()->getContents();

        $products = json_decode($products, true);

        $order =  [
            'orserStatus' => 'trouble',
            "orderType" => "fizik",
            "orderMethod" => "test",
            "magazine" => "test",
            "number" => "15121997",
            "lastName" => $fio[1],
            "firstName" => $fio[0],
            "patronymic" => $fio[2],
            'customerComment' => $data['comment'],
            'product' => [
                "article" => $data['article'],
                "manufacturer" => $data['manufacturer'],
                "name" => "Чашка для супа Сабина Сине-золотая лента (0.3 л) с блюдцами 02120623-0767 Leander",
            ]
        ];

        $response = $client->request('POST', '/api/v5/orders/create', [
            'verify' => false,
            'form_params' => [
                'apiKey' => 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb',
                "order" => json_encode($order)
            ]
        ]);

        $response = json_decode($response->getBody()->getContents(), true);
        dd($response);
    }
}