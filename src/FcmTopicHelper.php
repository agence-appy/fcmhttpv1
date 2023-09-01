<?php

namespace Appy\FcmHttpV1;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class FcmTopicHelper
{
    /**
     * Subscribe tokens to a specific topic
     * @param array $tokens
     * @param string $topic
    */
    public static function subscribeToTopic($tokens, $topic)
    {
        if (!isset($topic)) {
            throw new Exception("Topic can't be null");
        }

        if (!is_array($tokens)) {
            throw new Exception("Tokens need to be passed as an array");
        }

        if (is_array($tokens) && count($tokens) > 999) {
            throw new Exception("Too much tokens, limit is 999, received " . count($tokens));
        }

        $url = "https://iid.googleapis.com/iid/v1:batchAdd";

        $oauthToken = FcmGoogleHelper::configureClient();

        $headers = [
            'Authorization' => 'Bearer ' . $oauthToken,
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
            Log::error("[Error] on subscribe to topic: " . $topic, [$e->getMessage()]);

            return $e;
        }
    }

    /**
     * Subscribe tokens to a specific topic
     * @param array $tokens
     * @param string $topic
    */
    public static function unSubscribeToTopic($tokens, $topic)
    {
        $url = "https://iid.googleapis.com/iid/v1:batchRemove";

        $oauthToken = FcmGoogleHelper::configureClient();

        $headers = [
            'Authorization' => 'Bearer ' . $oauthToken,
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

    /**
     * Subscribe a token to a specific topic
     * @param array $token
     * @return array [topic, addDate]
    */
    public static function getTopicsByToken($token)
    {
        $url = "https://iid.googleapis.com/iid/info/" . $token . '?details=true';

        $oauthToken = FcmGoogleHelper::configureClient();

        $headers = [
            'Authorization' => 'Bearer ' . $oauthToken,
            'Content-Type' =>  'application/json',
        ];

        $client = new Client();

        try {
            $request = $client->get($url, [
                'headers' => $headers,
            ]);

            $response = $request->getBody()->getContents();

            $decoded_res = json_decode($response, true)["rel"]["topics"];

            $topics = [];

            foreach ($decoded_res as $k => $v) {
                array_push($topics, [$k, $v['addDate']]);
            }

            return $topics;
        } catch (Exception $e) {
            Log::error("[ERROR] get topics by token ", [$e->getMessage()]);

            return $e;
        }
    }
}
