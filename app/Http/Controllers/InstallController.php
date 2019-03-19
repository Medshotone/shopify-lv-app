<?php

namespace App\Http\Controllers;
use App\Install;
use RandomLib;
use Illuminate\Http\Request;

class InstallController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('install');
    }
    public function creat_user(){
        $install = new Install;
        $store = $_POST['store'];
        $factory = new RandomLib\Factory;
        $generator = $factory->getMediumStrengthGenerator();
        $nonce = $generator->generateString(20);

        $api_key = env('SHOPIFY_APIKEY');
        $scopes = env('SHOPIFY_SCOPES');
        $redirect_uri = urlencode(env('SHOPIFY_REDIRECT_URI'));
        if(Install::all('store')->where('store', $store)->isNotEmpty()){
            $url = "https://{$store}/admin/oauth/authorize?client_id={$api_key}&scope={$scopes}&redirect_uri={$redirect_uri}&state={$nonce}";
            return redirect($url);
        }else{
            $install->nonce = $nonce;
            $install->access_token = '';
            $install->store = $store;
            if($install->save()){
                $url = "https://{$store}/admin/oauth/authorize?client_id={$api_key}&scope={$scopes}&redirect_uri={$redirect_uri}&state={$nonce}";
                return redirect($url);
            }
        };

    }
}
