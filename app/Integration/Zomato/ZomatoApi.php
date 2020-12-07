<?php

namespace App\Integration\Zomato;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ZomatoApi
{
    private $key;
    private $limit;

    public function __construct($config)
    {
        $this->key = $config['key'];
        $this->limit = $config['limit'] ?? 8;
    }

    public function search($query, $lon, $lat)
    {
        $body = [
            'q' => $query,
            'lat' => $lat,
            'lon' => $lon,
            'sort' => 'real_distance',
            'order' => 'asc',
            'count' => $this->limit
        ];
        $headers = ['user-key' => $this->key];
        
        $client = new Client();
        $response = $client->get('https://developers.zomato.com/api/v2.1/search', [
            'query' => $body,
            'headers' => $headers
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function dailymenu($resIs)
    {
        $body = [
            'res_id' => $resIs
        ];
        $headers = ['user-key' => $this->key];
        
        try {            
            $client = new Client();
            $response = $client->get('https://developers.zomato.com/api/v2.1/dailymenu', [
                'query' => $body,
                'headers' => $headers
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            if (!$e->hasResponse()){
                Log::warning("Zomato: cannot get daily menu", [
                    'resource' => $resIs
                ]);
            }
        }
        return [];
    }
}