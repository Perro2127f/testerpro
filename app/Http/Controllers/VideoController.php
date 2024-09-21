<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserApiKey;
use App\Models\ApiUsageLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class VideoController extends Controller
{
    // Validar API Key y parámetros iniciales
    public function verifyApiKey(Request $request)
    {


        // Validación de la solicitud POST

        // if (!$request->isMethod('post')) {
        //     return response()->json([
        //         'error' => 'Method Not Allowed',
        //         'message' => 'The GET method is not supported for this route. Supported methods: POST.'
        //     ], 405);
        // }

        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid or missing URL'], 400);
        }

        // Extraer el Bearer token de los headers
        $apiKey = $request->bearerToken();
        if (!$apiKey) {
            return response()->json(['error' => 'No API key provided'], 401);
        }

        // Verificar si el API Key existe y está activo
        $userApiKey = UserApiKey::where('api_key', $apiKey)->first();

        if (!$userApiKey || $userApiKey->status !== 'active') {
            return response()->json(['error' => 'Invalid or inactive API key'], 401);
        }

        // Verificar créditos disponibles
        if ($userApiKey->credits <= 0) {
            return response()->json(['error' => 'Insufficient credits'], 403);
        }

        // Verificar la expiración del API Key
        if ($userApiKey->expiration && $userApiKey->expiration < now()) {
            return response()->json(['error' => 'API key expired'], 403);
        }

        // Verificar IP permitida
        if ($userApiKey->ip_address !== '0.0.0.0' && $userApiKey->ip_address !== $request->ip()) {
            return response()->json(['error' => 'Unauthorized IP address'], 403);
        }

        // Si todas las validaciones pasaron, procedemos con la verificación de URL
        return $this->processUrl($request, $userApiKey);
    }

    // Procesar la URL validada y redirigir según la plataforma
    public function processUrl(Request $request, UserApiKey $userApiKey)
    {
        $url = $request->input('url');

        if (strpos($url, 'facebook.com') !== false) {
            // Si la URL es de Facebook, llamamos al controlador correspondiente
            return app('App\Http\Controllers\FacebookDownloader')->download($request, $userApiKey);
        } else {
            return response()->json(['error' => 'Only Facebook URLs are supported currently'], 400);
        }
    }
}
