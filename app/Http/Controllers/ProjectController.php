<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseStorageService
{
    protected $baseUrl;
    protected $apiKey;
    protected $bucket;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.supabase.url'), '/');
        $this->apiKey  = config('services.supabase.key');
        $this->bucket  = config('services.supabase.bucket', 'public');
    }

    /**
     * Upload a file to Supabase Storage and return its public URL.
     */
    public function upload(string $path, string $contents): ?string
    {
        try {
            $url = "{$this->baseUrl}/storage/v1/object/{$this->bucket}/{$path}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'apikey'        => $this->apiKey,
                'Content-Type'  => 'application/octet-stream',
            ])->put($url, $contents);

            if ($response->failed()) {
                Log::error("Supabase upload failed: " . $response->body());
                return null;
            }

            return $this->getPublicUrl($path);
        } catch (\Exception $e) {
            Log::error("Supabase upload exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a file from Supabase Storage.
     */
    public function delete(string $path): bool
    {
        try {
            // Strip base public URL if passed accidentally
            $relativePath = str_replace(
                "{$this->baseUrl}/storage/v1/object/public/{$this->bucket}/",
                '',
                $path
            );

            $url = "{$this->baseUrl}/storage/v1/object/{$this->bucket}/{$relativePath}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'apikey'        => $this->apiKey,
            ])->delete($url);

            if ($response->failed()) {
                Log::error("Supabase delete failed: " . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Supabase delete exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate a public URL for a file in Supabase Storage.
     */
    public function getPublicUrl(string $path): string
    {
        return "{$this->baseUrl}/storage/v1/object/public/{$this->bucket}/{$path}";
    }
}
