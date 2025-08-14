<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class ProcessVoiceSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-voice-search {audio}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes a base64 audio input and returns the transcribed text using OpenAI Whisper';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $audioData = $this->argument('audio');

        // Create a temporary WAV file
        $audioFile = tempnam(sys_get_temp_dir(), 'voice') . '.wav';
        file_put_contents($audioFile, base64_decode($audioData));

        // Send to OpenAI Whisper API
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/audio/transcriptions', [
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.openai.key'),
            ],
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($audioFile, 'r'),
                    'filename' => 'voice.wav'
                ],
                [
                    'name' => 'model',
                    'contents' => 'whisper-1'
                ]
            ]
        ]);

        // Get transcription text
        $result = json_decode($response->getBody(), true);

        // Clean up temp file
        unlink($audioFile);

        $this->info('Transcribed Text: ' . ($result['text'] ?? 'No text returned'));
    }
}
