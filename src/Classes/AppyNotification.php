<?php

namespace Appy\FcmHttpV1\Classes;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Appy\FcmHttpV1\Helpers\AppyGoogleHelper;


class AppyNotification
{
    protected $title;
    protected $body;
    protected $icon;
    protected $click_action;
    protected $token;
    protected $topic;

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function setClickAction($click_action)
    {
        $this->click_action = $click_action;
        return $this;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;

    }

    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    public function send()
    {
        // Token and topic combinaison verification
        if ($this->token != null && $this->topic != null) {
            throw new Exception("A notification need to have at least one target: token or topic. Please select only one type of target.");
        }

        // Empty token or topic verification
        if ($this->token == null && $this->topic == null) {
            throw new Exception("A notification need to have at least one target: token or topic. Please add a target using setToken() or setTopic().");
        }

        // Title verification
        if (!isset($this->title)) {
            throw new Exception('Empty notification title. Please add a title to the notification. Please use the setTitle() method.');
        }

        // Body verification
        if (!isset($this->body)) {
            throw new Exception('Empty notification body. Please add a body to the notification. Please use the setBody() method');
        }

        // Icon verification
        if (!file_exists(asset($this->icon))) {
            throw new Exception("Icon not found. Please verify the path of your icon(Path of the icon you tried to set: " . asset($this->icon));
        }

        return $this->prepareSend();
    }

    private function prepareSend()
    {
        if (isset($this->topic)) {
            $data = [
                "message" => [
                    "topic" => $this->topic,
                    "webpush" => [
                        "notification" => [
                            "title" => $this->title,
                            "body" => $this->body,
                            "icon" => asset($this->icon),
                            "click_action" => $this->link ?? ''
                        ],
                    ]
                ]
            ];
        } elseif (isset($this->token)) {
            $data = [
                "message" => [
                    "token" => $this->token,
                    "webpush" => [
                        "notification" => [
                            "title" => $this->title,
                            "body" => $this->body,
                            "icon" => asset($this->icon),
                            "click_action" => $this->link ?? ''
                        ],
                    ]
                ]
            ];
        }

        $encodedData = json_encode($data);

        return $this->handleSend($encodedData);
    }

    private function handleSend($encodedData)
    {
        $url = config('appy_firebase.fcm_api_url');

        $oauthToken = AppyGoogleHelper::configureClient();

        $headers = [
            'Authorization' => 'Bearer ' . $oauthToken,
            'Content-Type' =>  'application/json',
        ];

        $client = new Client();

        try {
            $request = $client->post($url, [
                'headers' => $headers,
                "body" => $encodedData,
            ]);

            Log::info("[Notification] SENT", [$encodedData]);

            $response = $request->getBody();

            return $response;
        } catch (Exception $e) {
            Log::error("[Notification] ERROR", [$e->getMessage()]);

            return $e;
        }
    }
}
