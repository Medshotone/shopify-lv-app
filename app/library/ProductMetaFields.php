<?php

namespace App\library;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\library\HmacCheck;
use Illuminate\Support\Facades\DB;

class ProductMetaFields
{
    public static function getAllProductMetaFields($GET_from_shopify, $product_id){
        $secret_key = env('SHOPIFY_SECRET');
        if (HmacCheck::hmac_calc($GET_from_shopify, $secret_key)) {
            $shop = $GET_from_shopify['shop'];
            $DB_result = DB::table('installs')
                ->where('store',$shop)->get('access_token');

            if (!empty($DB_result)){
                $client = new Client();

                $response = $client->request(
                    'GET',
                    "https://{$shop}/admin/products/{$product_id}/metafields.json",
                    [
                        'query' => [
                            //'fields' => 'id,images,title,variants',
                            'access_token' => $DB_result[0]->access_token
                        ]
                    ]
                );
                $result = json_decode($response->getBody()->getContents(), true);

                $simple_metadields = array_column($result['metafields'], null, 'id');
                unset($result);
                return $simple_metadields;
                //return $result['metafields'];
            }else{

                echo 'Что то пошло не так, попробуйте еще раз.';
            }
        }else{

            echo 'Неверные входные данные';
        }
    }
}
