<?php

use GuzzleHttp\Client;

class NotificationService {
    private $firebaseServerKey;

    public function __construct() {
        $this->firebaseServerKey = getenv('FIREBASE_SERVER_KEY');
    }

    public function sendNotification($to, $title, $body) {
        $client = new Client();

        $response = $client->post('https://fcm.googleapis.com/fcm/send', [
            'headers' => [
                'Authorization' => 'key=' . $this->firebaseServerKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'to' => $to,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
