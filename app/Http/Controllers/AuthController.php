<?php

namespace App\Http\Controllers;
use App\Install;
use Illuminate\Support\Facades\DB;
use RandomLib;
use Carbon\Carbon;
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
            exit; //or redirect to an error page
        }

        $one_minute_ago = Carbon::now()->subSeconds(60)->timestamp;
        if ($query['timestamp'] < $one_minute_ago) {
            //exit; //or redirect to an error page
        }

        $hmac = $query['hmac'];
        $store = $query['shop'];
        unset($query['hmac']);

        foreach ($query as $key => $val) {
            $params[] = "$key=$val";
        }

        asort($params);
        $params = implode('&', $params);
        $calculated_hmac = hash_hmac('sha256', $params, $secret_key);
        if ($hmac == $calculated_hmac) {
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
        }

        if (DB::table('installs')->where('nonce', $nonce)->where('store',$store)->get('id')) {

            var_dump(DB::table('installs')->where('nonce', $nonce)->where('store',$store)->get('id'));
        }
    }
}
