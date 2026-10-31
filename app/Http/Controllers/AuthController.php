<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
class AuthController extends Controller
{
    public function generateCredentials(Request $request){

    // Validasi port 3111
    if ($request->getPort() != 3111) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak!',
                'current_port' => $request->getPort()
            ], 403);
        }

    // Generate API Key (Secure Random + Checksum)
    $prefix = 'sk_live_';
    $randomBytes = bin2hex(random_bytes(24)); // Generate 24 random karakter hex
    $secretKey = env('JWT_SECRET','super_secret_key_jwt_supermarket_2026_!@#');

    // Buar checksum HMAC SHA256 untuk memastikan validitas API Key
    $checksum = substr(hash_hmac('sha256', $randomBytes, $secretKey), 0, 8); // Ambil 8 karakter pertama dari hash
    $apiKey = $prefix . $randomBytes . '_' . $checksum; // Format API Key

    // Generate JWT Token
    $issuedAt = time();
    $payload = [
        'iss' => 'supermatket-api',
        'iat' => $issuedAt,
        'exp' => $issuedAt + 3600, // Durasi token 1 jam
        'data' => [
            'client_id' => 'client_'. Str::random(8),
            'role' => 'api_consumer'
        ]
     ];
     $jwtToken = JWT::encode($payload, $secretKey, 'HS256');
     return response()->json([
        'stastus' => 'success',
        'message' => 'Kredensial berhasil digenerate',
        'credentials' => [
            'api_key' => $apiKey,
            'jwt_token' => $jwtToken,
            'expires_in' => 3600
        ]
     ], 200);
    }
}
