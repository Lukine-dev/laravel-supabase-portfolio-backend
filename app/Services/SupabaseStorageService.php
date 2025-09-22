<?php
// app/Services/SupabaseStorageService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseStorageService
{
    protected $url;
    protected $key;
    protected $bucket;

    public function __construct()
    {
        $this->url = rtrim(env('SUPABASE_URL'), '/');
        $this->key = env('SUPABASE_KEY'); // Use service_role key on backend
        $this->bucket = env('SUPABASE_STORAGE_BUCKET');
    }

    protected function headers($mimeType = null)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->key,
            'apikey' => $this->key,
        ];

        if ($mimeType) {
            $headers['Content-Type'] = $mimeType;
        }

        return $headers;
    }

    public function upload($path, $fileContents, $mimeType = null)
    {
        try {
            $uploadUrl = "{$this->url}/storage/v1/object/{$this->bucket}/{$path}";

            $response = Http::withHeaders($this->headers($mimeType))
                ->withBody($fileContents, $mimeType ?: 'application/octet-stream')
                ->put($uploadUrl);

            if ($response->successful()) {
                return $path;
            }

            Log::error('Supabase upload failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'path' => $path
            ]);

            throw new \Exception("Supabase upload failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error('Supabase upload error', ['error' => $e->getMessage()]);
            throw new \Exception("Supabase upload error: " . $e->getMessage());
        }
    }

    public function uploadImage($path, $imageFile)
    {
        $imageContents = file_get_contents($imageFile->getRealPath());
        $mimeType = $imageFile->getMimeType();

        return $this->upload($path, $imageContents, $mimeType);
    }

    public function delete($path)
    {
        try {
            $deleteUrl = "{$this->url}/storage/v1/object/{$this->bucket}/{$path}";

            $response = Http::withHeaders($this->headers())
                ->delete($deleteUrl);

            if ($response->successful()) {
                return true;
            }

            Log::error('Supabase delete failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'path' => $path
            ]);

            throw new \Exception("Supabase delete failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error('Supabase delete error', ['error' => $e->getMessage()]);
            throw new \Exception("Supabase delete error: " . $e->getMessage());
        }
    }

    public function getPublicUrl($path)
    {
        // Works only if bucket is public
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$path}";
    }

    public function getSignedUrl($path, $expiresIn = 3600)
    {
        try {
            $signedUrlEndpoint = "{$this->url}/storage/v1/object/sign/{$this->bucket}/{$path}";

            $response = Http::withHeaders($this->headers('application/json'))
                ->post($signedUrlEndpoint, [
                    'expiresIn' => $expiresIn, // in seconds
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->url . $data['signedURL'];
            }

            Log::error('Supabase signed URL failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'path' => $path
            ]);

            throw new \Exception("Supabase signed URL failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error('Supabase signed URL error', ['error' => $e->getMessage()]);
            throw new \Exception("Supabase signed URL error: " . $e->getMessage());
        }
    }
}
