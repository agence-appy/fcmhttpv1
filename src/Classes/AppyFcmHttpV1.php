<?php

namespace Appy\FcmHttpV1\Classes;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class AppyFcmHttpV1
{
    public static function subscribeToTopic($token, $topic)
    {
        $url = "https://iid.googleapis.com/iid/v1/" . $token . "/rel/topics/" . $topic;

        $headers = [
            'Authorization' => 'key=' . config('appy_firebase.fcm_api_server_key'),
            'Content-Type' =>  'application/json',
        ];

        $client = new Client();

        try {
            $request = $client->post($url, [
                'headers' => $headers,
            ]);

            $response = $request->getBody();

            Log::info('Subscribed to topic: ' . $topic, [$response]);

            return $response;
        } catch (Exception $e) {
            Log::error("[Error] on subscribe to topic: " . $topic . " ERROR", [$e->getMessage()]);

            return $e;
        }
    }

    public static function unSubscribeToTopic($token, $topic)
    {
        $url = "https://iid.googleapis.com/iid/v1/" . $token . "/rel/topics/" . $topic;

        $headers = [
            'Authorization' => 'key=' . config('appy_firebase.fcm_api_server_key'),
            'Content-Type' =>  'application/json',
        ];

        $client = new Client();

        try {
            $request = $client->delete($url, [
                'headers' => $headers,
            ]);

            $response = $request->getBody();

            Log::info('[SUCCESS] unsubscribe to topic: ' . $topic, [$response]);

            return $response;
        } catch (Exception $e) {
            Log::error("[ERROR] unsubscribe to topic: " . $topic, [$e->getMessage()]);

            return $e;
        }
    }

    public static function getTopicsByUser($token)
    {
        $url = "https://iid.googleapis.com/iid/info/" . $token . '?details=true';

        $headers = [
            'Authorization' => 'key=' . config('appy_firebase.fcm_api_server_key'),
            'Content-Type' =>  'application/json',
        ];

        $client = new Client();

        try {
            $request = $client->get($url, [
                'headers' => $headers,
            ]);

            $response = $request->getBody();

            Log::info('[SUCCES] get topics by user', [$response]);

            return $response;
        } catch (Exception $e) {
            Log::error("[ERROR] get topics by user ", [$e->getMessage()]);

            return $e;
        }
    }
}
