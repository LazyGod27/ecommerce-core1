<?php

namespace App\Console\Commands;

use App\Models\ApiKey;
use Illuminate\Console\Command;

class GenerateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:generate 
                            {--name= : Name for the API key}
                            {--type=external : Type of API key (external, internal, webhook)}
                            {--permissions=* : Permissions for the API key}
                            {--expires= : Expiration date (Y-m-d H:i:s)}
                            {--description= : Description for the API key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new API key for external systems';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?: $this->ask('Enter API key name');
        
        if (!$name) {
            $this->error('API key name is required');
            return 1;
        }

        $type = $this->option('type');
        $permissions = $this->option('permissions');
        $expires = $this->option('expires');
        $description = $this->option('description');

        // Convert permissions array if it's a single value
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        // Parse expiration date
        $expiresAt = null;
        if ($expires) {
            try {
                $expiresAt = \Carbon\Carbon::parse($expires);
            } catch (\Exception $e) {
                $this->error('Invalid expiration date format. Use Y-m-d H:i:s');
                return 1;
            }
        }

        try {
            $apiKey = ApiKey::generate($name, $type, $permissions, $expiresAt);
            
            if ($description) {
                $apiKey->update(['description' => $description]);
            }

            $this->info('API key generated successfully!');
            $this->line('');
            $this->line('API Key Details:');
            $this->line('Name: ' . $apiKey->name);
            $this->line('Key: ' . $apiKey->key);
            $this->line('Secret: ' . $this->generateSecret());
            $this->line('Type: ' . $apiKey->type);
            $this->line('Permissions: ' . implode(', ', $apiKey->permissions));
            $this->line('Expires: ' . ($apiKey->expires_at ? $apiKey->expires_at->format('Y-m-d H:i:s') : 'Never'));
            $this->line('Description: ' . ($apiKey->description ?: 'None'));
            
            $this->line('');
            $this->warn('IMPORTANT: Save the secret key securely. It will not be shown again!');
            
            return 0;

        } catch (\Exception $e) {
            $this->error('Failed to generate API key: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Generate a secret key for display
     */
    private function generateSecret()
    {
        return 'sk_' . \Illuminate\Support\Str::random(32);
    }
}