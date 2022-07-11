<?php

namespace Appy\FcmHttpV1;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Appy\FcmHttpV1\FcmGoogleHelper;


class FcmNotification
{
    protected $title;
    protected $body;
    protected $icon;
    protected $click_action;
    protected $token;
    protected $topic;

    /** 
     *Title of the notification.
     *@param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /** 
     *Body of the notification.
     *@param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /** 
     *Icon of the notification.
     *@param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     *Link of the notification when user click on it.
     *@param string $click_action
     */
    public function setClickAction($click_action)
    {
        $this->click_action = $click_action;
        return $this;
    }

    /**
     *Token used to send notification to specific device. Unusable with setTopic() at same time.
     *@param string $string
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     *Topic of the notification. Unusable with setToken() at same time.
     *@param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * Verify the conformity of the notification. If everything is ok, send the notification.
     */
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

        if ($this->token != null && !is_string($this->token)) {
            throw new Exception('Token format error. Received: ' . gettype($this->token) . ". Expected type: string");
        }

        // Title verification
        if (!isset($this->title)) {
            throw new Exception('Empty notification title. Please add a title to the notification with the setTitle() method.');
        }

        // Body verification
        if (!isset($this->body)) {
            throw new Exception('Empty notification body. Please add a body to the notification with the setBody() method');
        }

        // Icon verification
        if ($this->icon !=null && !file_exists(public_path($this->icon))) {
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
                            "icon" => $this->icon !=null ? asset($this->icon) : '',
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
                            "icon" => $this->icon !=null ? asset($this->icon) : '',
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
        $url = config('fcm_config.fcm_api_url');

        $oauthToken = FcmGoogleHelper::configureClient();

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
