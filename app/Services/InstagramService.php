<?php

namespace App\Services;

use GuzzleHttp\Client;

class InstagramService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getInstagramBusinessAccountId($accessToken)
    {
        $response = $this->client->get('https://graph.instagram.com/me', [
            'query' => [
                'fields' => 'id,instagram_business_account',
                'access_token' => $accessToken
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        
        if (isset($data['instagram_business_account'])) {
            return $data['instagram_business_account']['id'];
        } else {
            return null;
        }
    }

    public function getInstagramMediaIds($igUserId, $accessToken)
    {
        $response = $this->client->get("https://graph.instagram.com/{$igUserId}/media", [
            'query' => [
                'access_token' => $accessToken
            ]
        ]);

        $mediaData = json_decode($response->getBody(), true);
        return array_column($mediaData['data'], 'id') ?? [];
    }
}