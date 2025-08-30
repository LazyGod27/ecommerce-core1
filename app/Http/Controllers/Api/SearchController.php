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
        try {
            // Check if OpenAI API key is configured
            $openaiKey = config('services.openai.key');
            if (!$openaiKey) {
                throw new \Exception('OpenAI API key not configured');
            }

            // Send audio to OpenAI Whisper API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $openaiKey,
            ])->attach(
                'file', file_get_contents($audioFile), 'audio.wav'
            )->post('https://api.openai.com/v1/audio/transcriptions', [
                'model' => 'whisper-1',
                'response_format' => 'json'
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['text'] ?? '';
            } else {
                throw new \Exception('OpenAI API request failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            \Log::error('Voice transcription failed: ' . $e->getMessage());
            throw new \Exception('Voice transcription failed: ' . $e->getMessage());
        }
    }
}