<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenTok\OpenTok;
use OpenTok\Role;

class VideoProviderController extends Controller
{
    public function selectProvider(string $name)
    {
        if (strtolower($name) === 'vonage') {
            $apiKey = env('VONAGE_API_KEY');
            $apiSecret = env('VONAGE_API_SECRET');

            $opentok = new OpenTok($apiKey, $apiSecret);

            $session = $opentok->createSession();

            $token = $session->generateToken(array(
                'role'       => Role::MODERATOR,
                'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
                'data'       => 'observer',
                'initialLayoutClassList' => array('focus')
            ));

            return view('vonage.observer')->with([
                'token' => $token,
                'session_id' => $session->getSessionId(),
                'api_key'   => $apiKey,
            ]);
        }

        if (strtolower($name) === 'zoom') {

            $sessionName = $this->createSessionId();

            $payload = [
                'app_key' => env('ZOOM_SDK_KEY'),
                'role_type' => 1,
                'tpc' => $sessionName,
                'version' => 1,
                'iat' => time(),
                'exp' => time() + 86400,
            ];

            return view('zoom.observer')->with([
                'token' => $this->createJWTToken($payload),
                'sessionName' => $sessionName,
            ]);
        }
    }

    public static function createSessionId(): string
    {
        $length = 20;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = "ZM_";
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    private function createJWTToken(array $payload): string
    {

        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        // Create token payload as a JSON string
        $payload = json_encode($payload);

        // Encode Header to Base64Url String
        $base64UrlHeader = base64_encode($header);

        // Encode Payload to Base64Url String
        $base64UrlPayload = base64_encode($payload);

        $secret = env('ZOOM_SDK_SECRET');

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader.'.'.$base64UrlPayload, $secret, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = base64_encode($signature);

        // Create JWT
        $jwt = $base64UrlHeader.'.'.$base64UrlPayload.'.'.$base64UrlSignature;

        return $jwt;
    }

    function joinVonage(string $sessionId)
    {

        $apiKey = env('VONAGE_API_KEY');
        $apiSecret = env('VONAGE_API_SECRET');

        $opentok = new OpenTok($apiKey, $apiSecret);

        $token = $opentok->generateToken($sessionId);

        return view('vonage.patient')->with([
            'token' => $token,
            'session_id' => $sessionId,
            'api_key'   => $apiKey,
        ]);
    }

    function joinZoom(string $sessionName)
    {

        $payload = [
            'app_key' => env('ZOOM_SDK_KEY'),
            'role_type' => 1,
            'tpc' => $sessionName,
            'version' => 1,
            'iat' => time(),
            'exp' => time() + 86400,
        ];

        return view('zoom.patient')->with([
            'token' => $this->createJWTToken($payload),
            'sessionName' => $sessionName,
        ]);
    }
}
