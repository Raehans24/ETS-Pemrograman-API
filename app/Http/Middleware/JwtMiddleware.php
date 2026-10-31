<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization');

        // Cek header dan formatnya
        if(!$header || !str_starts_with($header, 'Bearer ')){
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak! Token JWT tidak ditemukan pada header'
            ], 401);
        }

        // Ambil token dari string Bearer
        $token = str_replace('Bearer ', '', $header);
        $secretKey = env('JWT_SECRET', 'super_secret_key_jwt_supermarket_2026_!@#');

        try {
            // Decode dan validasi signature juga masa berlaku token
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

            // Simpan Payload ke request kalau perlu data user/client di controller
            $request->merge(['jwt_payload'=> (array) $decoded]);
        } catch (\Exception $e) {
            return response ()->json([
                'status' => 'error',
                'message' => 'Akses ditolak! Token JWT tidak valid atau kadalwarsa' . $e->getMessage()
            ], 401);
        }
        return $next($request);
    
}
