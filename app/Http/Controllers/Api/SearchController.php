<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function voiceSearch(Request $request)
    {
        $request->validate([
            'audio_file' => 'required|file|mimes:mp3,wav,m4a|max:10240'
        ]);

        try {
            // Process voice to text
            $transcribedText = $this->transcribeAudio($request->file('audio_file'));
            
            // Search products based on transcribed text
            $products = Product::where('name', 'like', '%' . $transcribedText . '%')
                ->orWhere('description', 'like', '%' . $transcribedText . '%')
                ->with(['reviews'])
                ->paginate(15);

            return response()->json([
                'transcribed_text' => $transcribedText,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Voice search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function transcribeAudio($audioFile)
    {
        // This is a placeholder implementation
        // In production, you would integrate with a speech-to-text service like:
        // - Google Cloud Speech-to-Text
        // - Amazon Transcribe
        // - Azure Speech Services
        // - OpenAI Whisper API

        // For demo purposes, return a sample text
        return 'sample product search';
        
        // Real implementation would look like:
        /*
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.speech.key'),
        ])->attach(
            'audio', file_get_contents($audioFile), 'audio.wav'
        )->post(config('services.speech.endpoint'));

        return $response->json()['transcript'] ?? '';
        */
    }
}