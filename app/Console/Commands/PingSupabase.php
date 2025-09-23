<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PingSupabase extends Command
{
    protected $signature = 'ping:supabase';
    protected $description = 'Send a lightweight HEAD request to Supabase daily to prevent sleeping';

    public function handle(): void
    {
        $url = config('services.supabase.url');

        if (! $url) {
            $this->error("❌ No Supabase URL configured. Please set SUPABASE_URL in .env");
            return;
        }

        try {
            $response = Http::retry(3, 200)
                ->withOptions(['timeout' => 5])
                ->head($url);

            if ($response->successful()) {
                $this->info("✅ Supabase pinged successfully (status {$response->status()})");
            } else {
                $this->warn("⚠️ Supabase responded with status {$response->status()}");
            }
        } catch (\Exception $e) {
            $this->error("❌ Failed to ping Supabase: " . $e->getMessage());
        }
    }
}
