<?php

namespace App\Http\Controllers;

use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseStorageService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Upload file to Supabase Storage
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:5120|mimes:jpg,jpeg,png,pdf,docx,mp4,mp3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $path = 'uploads/' . uniqid() . '_' . $file->getClientOriginalName();

            $url = $this->supabase->upload($path, file_get_contents($file->getRealPath()));

            if (!$url) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Upload failed'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'url'    => $url,
                'path'   => $path
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get public URL of a file
     */
    public function getFileUrl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $url = $this->supabase->getPublicUrl($request->path);

        return response()->json([
            'status' => 'success',
            'url'    => $url
        ]);
    }

    /**
     * Delete file from Supabase
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $deleted = $this->supabase->delete($request->path);

        if (!$deleted) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Delete failed'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'File deleted successfully'
        ]);
    }
}
