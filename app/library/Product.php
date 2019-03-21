<?php

namespace App\library;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\library\HmacCheck;
use Illuminate\Support\Facades\DB;

class Product
{
    public static function getAllProduct($GET_from_shopify){
        $secret_key = env('SHOPIFY_SECRET');
        if (HmacCheck::hmac_calc($GET_from_shopify, $secret_key)) {
            $shop = $GET_from_shopify['shop'];
            $DB_result = DB::table('installs')
                ->where('store',$shop)->get('access_token');

            if (!empty($DB_result)){
                $client = new Client();

                $response = $client->request(
                    'GET',
                    "https://{$shop}/admin/products.json",
                    [
                        'query' => [
                            'fields' => 'id,images,title,variants,handle',
                            'access_token' => $DB_result[0]->access_token
                        ]
                    ]
                );

                $result = json_decode($response->getBody()->getContents(), true);
                return $result;
            }else{

                echo 'Что то пошло не так, попробуйте еще раз.';
            }
        }else{

            echo 'Неверные входные данные';
        }
    }

    public static function getAllProduct_list($GET_from_shopify){
        $secret_key = env('SHOPIFY_SECRET');
        if (HmacCheck::hmac_calc($GET_from_shopify, $secret_key)) {
            $shop = $GET_from_shopify['shop'];
            $DB_result = DB::table('installs')
                ->where('store',$shop)->get('access_token');

            if (!empty($DB_result)){
                $client = new Client();

                $response = $client->request(
                    'GET',
                    "https://{$shop}/admin/products.json",
                    [
                        'query' => [
                            'fields' => 'id,title,handle',
                            'access_token' => $DB_result[0]->access_token
                        ]
                    ]
                );

                $result = json_decode($response->getBody()->getContents(), true);
                return $result;
            }else{

                echo 'Что то пошло не так, попробуйте еще раз.';
            }
        }else{

            echo 'Неверные входные данные';
        }
    }

}
