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
    public static function updateProductMetaFields($GET_from_shopify, $owner_id, $values){
        $secret_key = env('SHOPIFY_SECRET');
        if (HmacCheck::hmac_calc($GET_from_shopify, $secret_key)) {
            $shop = $GET_from_shopify['shop'];
            $DB_result = DB::table('installs')
                ->where('store',$shop)->get('access_token');

            if (!empty($DB_result)){
                $headers = [
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => $DB_result[0]->access_token,
                ];
                $client = new Client([
                    'headers' => $headers
                ]);
                $result = 0;
                $target = 0;
                foreach ($values as $metafield_id => $value){
                    $target++;
                    $data = [
                        'metafield'=>[
                            'id'=>$metafield_id,
                            //'namespace'=>'tes',
                            'value'=>$value,
                            'value_type'=>'string',
                        ]
                    ];
                    $response = $client->request('PUT', "https://{$shop}/admin/products/{$owner_id}/metafields/{$metafield_id}.json",[
                        'json' => $data
                    ]);
                   if ($response->getStatusCode() == 200){
                       $result++;
                    }
                }

                echo "$result/$target измененно";
            }else{

                echo 'Что то пошло не так, попробуйте еще раз.';
            }
        }else{

            echo 'Неверные входные данные';
        }
    }

    public static function deleteProductMetaFields($GET_from_shopify, $owner_id, $metafield_id){
        $secret_key = env('SHOPIFY_SECRET');
        if (HmacCheck::hmac_calc($GET_from_shopify, $secret_key)) {
            $shop = $GET_from_shopify['shop'];
            $DB_result = DB::table('installs')
                ->where('store',$shop)->get('access_token');

            if (!empty($DB_result)){
                $headers = [
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => $DB_result[0]->access_token,
                ];
                $client = new Client([
                    'headers' => $headers
                ]);
                    $response = $client->request('DELETE', "https://{$shop}/admin/products/{$owner_id}/metafields/{$metafield_id}.json");
                    if ($response->getStatusCode() == 200){
                        echo 'DELETE';
                    }
            }else{

                echo 'Что то пошло не так, попробуйте еще раз.';
            }
        }else{

            echo 'Неверные входные данные';
        }
    }
    public static function createProductMetaFields($GET_from_shopify, $owner_id, $namespace, $key, $value, $value_type = 'string'){
        $secret_key = env('SHOPIFY_SECRET');
        if (empty($value_type)){
            $value_type ='string';
        }
        if (HmacCheck::hmac_calc($GET_from_shopify, $secret_key)) {
            $shop = $GET_from_shopify['shop'];
            $DB_result = DB::table('installs')
                ->where('store',$shop)->get('access_token');

            if (!empty($DB_result)){
                $data = [
                    'metafield'=>[
                        'key'       => $key,
                        'namespace' => $namespace,
                        'value'     => $value,
                        'owner_id'  => $owner_id,
                        'value_type'=> $value_type,
                    ]
                ];
                $headers = [
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => $DB_result[0]->access_token,
                ];
                $client = new Client([
                    'headers' => $headers
                ]);
                $response = $client->request('POST', "https://{$shop}/admin/products/{$owner_id}/metafields.json",[
                    'json' => $data
                ]);
                if ($response->getStatusCode() == 200){
                    echo "Созданно: $key";
                }
            }else{

                echo 'Что то пошло не так, попробуйте еще раз.';
            }
        }else{

            echo 'Неверные входные данные';
        }
    }
}
