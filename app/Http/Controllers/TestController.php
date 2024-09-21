<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testerFb()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/fb?url=https://web.facebook.com/reel/1567848187476878');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language: es-ES,es;q=0.9',
            'Cache-Control: max-age=0',
            'Authorization: Bearer BrbHbtaFpJ1Wr8zR8gLDMzCl7YeO9WqtiGTf6wM2Otgb9FF5iYWctCiekV6eM0rS',
            'Connection: keep-alive',
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: none',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36',
        ]);

        $response = curl_exec($ch);

        if(curl_errno($ch)){
            $error_msg = curl_error($ch);
        }

        curl_close($ch);

        if(isset($error_msg)) {
            return "cURL error: " . $error_msg;
        }

        return response()->json(json_decode($response, true));
    }
}
