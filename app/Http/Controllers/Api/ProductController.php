<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['reviews'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return response()->json($products);
    }

    public function productSearch(Request $request)
    {
        // Traditional search
        $query = Product::with(['reviews']);
        
        if ($request->has('q')) {
            // Text search
            $query->where('name', 'like', '%'.$request->q.'%')
                  ->orWhere('description', 'like', '%'.$request->q.'%');
        }
        
        // Voice search processing
        if ($request->has('voice_query')) {
            $voiceText = $this->processVoiceQuery($request->voice_query);
            $query->where('name', 'like', '%'.$voiceText.'%');
        }
        
        // Image search
        if ($request->hasFile('image_search')) {
            $imageResults = $this->processImageSearch($request->file('image_search'));
            $query->whereIn('id', $imageResults);
        }
        
        // Add filters, pagination, etc.
        return $query->paginate(15);
    }
    
    private function processVoiceQuery($audio)
    {
        // Integrate with speech-to-text API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.speech.key'),
        ])->post('https://api.speech-service.com/v1/recognize', [
            'audio' => base64_encode(file_get_contents($audio)),
        ]);
        
        return $response->json()['text'];
    }
    
    private function processImageSearch($image)
    {
        // Integrate with computer vision API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.vision.key'),
        ])->post('https://api.vision-service.com/v1/analyze', [
            'image' => base64_encode(file_get_contents($image)),
            'features' => ['objects', 'tags']
        ]);
        
        // Extract relevant product tags
        $tags = $response->json()['tags'];
        return Product::whereHas('tags', function($q) use ($tags) {
            $q->whereIn('name', $tags);
        })->pluck('id');
    }
}
