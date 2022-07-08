<?php

namespace Appy\FcmHttpV1;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class FcmTopicHelper
{   
    /**
     * Subscribe a token to a specific topic
     * @param array $tokens
     * @param string $topic
     */
    public static function subscribeToTopic($tokens, $topic)
    {   
        if(!isset($topic)){
            throw new Exception("Topic can't be null");
        }

        if(!is_array($tokens)){
            throw new Exception("Tokens needs to be passed as an array");
        }

        if(count($tokens) > 999){
            throw new Exception("Too much tokens, limit is 999");
        }

        $url = "https://iid.googleapis.com/iid/v1:batchAdd";

        $headers = [
            'Authorization' => 'key=' . config('fcm_config.fcm_api_server_key'),
            'Content-Type' =>  'application/json',
        ];

        $body = [
            "to" => "/topics/" . $topic,
            "registration_tokens" => $tokens
        ];

        $client = new Client();

        try {
            $request = $client->post($url, [
                'headers' => $headers,
                "body" => json_encode($body)
            ]);

            $response = $request->getBody();

            Log::info('Subscribed to topic: ' . $topic, [$response]);

            return $response;
        } catch (Exception $e) {
            Log::error("[Error] on subscribe to topic: " . $topic . " ERROR", [$e->getMessage()]);

            return $e;
        }
    }

    public static function unSubscribeToTopic($tokens, $topic)
    {
        $url = "https://iid.googleapis.com/iid/v1:batchRemove";


        $headers = [
            'Authorization' => 'key=' . config('fcm_config.fcm_api_server_key'),
            'Content-Type' =>  'application/json',
        ];

        $body = [
            "to" => "/topics/" . $topic,
            "registration_tokens" => $tokens
        ];

        $client = new Client();

        try {
            $request = $client->post($url, [
                'headers' => $headers,
                "body" => json_encode($body)
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
            'Authorization' => 'key=' . config('fcm_config.fcm_api_server_key'),
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
