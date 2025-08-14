<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Product;


class ImageSearchService
{
    public function searchByImage($imageFile)
    {
        $client = new Client();
        
        $response = $client->post('https://api.openai.com/v1/images/embeddings', [
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.openai.key'),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'image' => base64_encode(file_get_contents($imageFile)),
                'model' => 'clip-vit-base-patch32'
            ]
        ]);
        
        $embedding = json_decode($response->getBody(), true)['data']['embedding'];
        
        // Search products with similar embeddings
        return Product::query()
            ->orderByRaw('embedding <=> ?', [json_encode($embedding)])
            ->limit(10)
            ->get();
    }
}