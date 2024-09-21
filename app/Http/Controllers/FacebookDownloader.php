<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserApiKey;
use App\Models\ApiUsageLog;
use FbMediaDownloader\Downloader;  // Uso de la librería para descargar videos de Facebook

class FacebookDownloader extends Controller
{
    public function download(Request $request, UserApiKey $userApiKey)
    {
        $url = $request->input('url');

        // Llamada a la librería para obtener los datos del video
        $downloader = new Downloader();
        $downloader->set_url($url);
        $videoData = $downloader->generate_data();

        // Verificación del estado de la respuesta
        if ($videoData['status'] != 200) {
            return response()->json(['error' => 'Failed to fetch video data'], 500);
        }

        // Registrar el uso de la API en los logs
        $this->logApiUsage($userApiKey, $request->ip(), $url);

        // Decrementar créditos del usuario
        $userApiKey->decrement('credits');

        // Retornar la información del video en formato JSON
        return response()->json([
            'title' => $videoData['title'],
            'size_low_quality' => $videoData['dl_urls']['lowData']['sizeHuman'],
            'size_high_quality' => $videoData['dl_urls']['highData']['sizeHuman'],
            'url_low_quality' => $videoData['dl_urls']['low'],
            'url_high_quality' => $videoData['dl_urls']['high'],
        ]);
    }

    // Registrar el uso de la API en la tabla de logs
    private function logApiUsage(UserApiKey $userApiKey, $ipAddress, $endpoint)
    {
        ApiUsageLog::create([
            'user_api_key_id' => $userApiKey->id,
            'name_key' => $userApiKey->name_key,
            'endpoint' => '/fb',
            'ip_address' => $ipAddress,
            'success_use' => 1,
            'last_use' => now(),
        ]);
    }
}
