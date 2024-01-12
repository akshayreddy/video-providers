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
}
