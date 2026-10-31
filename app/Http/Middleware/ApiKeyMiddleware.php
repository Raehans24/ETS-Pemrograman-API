<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');

        // Validasi header 
        if(!$apiKey){
            return response()->json([
                'status' => 'error',
                'message'=> 'Akses ditolak! Header tidak ditemukan'
            ], 401);
        }

        // Validasi prefix
        if(!str_starts_with($apiKey, 'sk_live_')){
            return response()->json([
                'status' => 'error',
                'message'=> 'Akses ditolak! Prefix tidak valid'
            ], 401);
        }

        // Parsing struktur API Key untuk validasi checksum
        $parts = explode('_', str_replace('sk_live_', '', $apiKey));
        if(count($parts) !== 2){
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak! Struktur API Key tidak valid'
            ], 401);
        }

        $randomBytes = $parts[0];
        $providedChecksum = $parts[1];
        $secretKey = env('JWT_SECRET', 'super_secret_key_jwt_supermarket_2026_!@#');

        // HItung ulang checksum HMAC untuk validasi
        $expectedChecksum = substr(hash_hmac('sha256', $randomBytes, $secretKey), 0, 8);

        if(!hash_equals($expectedChecksum, $providedChecksum)){
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak! API Key tidak valid'
            ], 401);
        }

        return $next($request);
    }
}
