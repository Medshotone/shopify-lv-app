<?php

namespace App\Http\Controllers;
use App\library\HmacCheck;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function creat_user_token()
    {
        $api_key = env('SHOPIFY_APIKEY');
        $secret_key = env('SHOPIFY_SECRET');

        $query = $_GET;

        if (!isset($query['code'], $query['hmac'], $query['shop'], $query['state'], $query['timestamp'])) {
            echo 'Чего то не хватает: '; print_r($query);
        }

        $store = $query['shop'];

        if (HmacCheck::hmac_calc($query, $secret_key)) {
            $client = new Client();
            $response = $client->request(
                'POST',
                "https://{$store}/admin/oauth/access_token",
                [
                    'form_params' => [
                        'client_id' => $api_key,
                        'client_secret' => $secret_key,
                        'code' => $query['code']
                    ]
                ]
            );
            $data = json_decode($response->getBody()->getContents(), true);
            $access_token = $data['access_token'];

            $nonce = urlencode($query['state']);

            $DB_result = DB::table('installs')
                ->where('nonce', $nonce)
                ->where('store',$store)
                ->update(['access_token' => $access_token]);
            if ($DB_result == 1){
                $data = [
                    'link' => "https://{$store}/admin/apps/",
                    'link_text' => 'Вернуться в админ панель приложений.'
                ];
                return view('after_auth',$data);
            }else{
                DB::table('installs')
                    ->where('store',$store)
                    ->delete();
                echo 'Что то пошло не так, попробуйте еще раз.';
            }
        }else{
            echo 'Переданные данные не совпадают.';
        }

    }
}
