<?php

namespace App\library;

use Carbon\Carbon;
use Illuminate\Http\Request;

class HmacCheck
{
    public static function hmac_calc($query, $secret_key, $timestamp_check = false){
        $one_minute_ago = Carbon::now()->subSeconds(60)->timestamp;
        if ($timestamp_check == true and $query['timestamp'] < $one_minute_ago) {
            return false;
            exit;
        }
        $hmac = $query['hmac'];
        unset($query['hmac']);
        foreach ($query as $key => $val) {
            $params[] = "$key=$val";
        }
        asort($params);
        $params = implode('&', $params);
        $calculated_hmac = hash_hmac('sha256', $params, $secret_key);
        if ($hmac == $calculated_hmac){
            return true;
        }else{
            return false;
        }
    }
}
