<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Services\SupabaseStorageService;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // or Imagick if available

class ExperienceController extends Controller
{
    protected $supabaseStorage;

    public function __construct()
    {
        $this->supabaseStorage = new SupabaseStorageService();
    }

    public function index($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $experiences = $service->experiences()->get();

        return view('admin.experiences.index', compact('service', 'experiences'));
    }

    public function create($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        return view('admin.experiences.create', compact('service'));
    }

    public function edit($serviceId, $experienceId)
    {
        $service = Service::findOrFail($serviceId);
        $experience = Experience::findOrFail($experienceId);

        return view('admin.experiences.edit', compact('service', 'experience'));
    }

    public function store(Request $request, $serviceId)
    {
        $validated = $request->validate([
            'company_name'      => 'required|string|max:255',
            'position'          => 'required|string|max:255',
            'description'       => 'required|string',
            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after:start_date',
            'currently_working' => 'boolean',
            'skills'            => 'required|string',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        // Handle uploads
        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->handleImageUpload($request->file('logo'), 'experiences/logo');
        }

        if ($request->hasFile('images')) {
            $validated['images'] = $this->handleImageUpload($request->file('images'), 'experiences/images');
        }

        // Convert skills string into array
        $validated['skills'] = array_map('trim', explode(',', $validated['skills']));

        $service = Service::findOrFail($serviceId);
        $service->experiences()->create($validated);

        return redirect()->route('admin.services.show', $service->slug)
            ->with('success', 'Experience created successfully.');
    }

    public function update(Request $request, $serviceId, $experienceId)
    {
        $experience = Experience::findOrFail($experienceId);

        $validated = $request->validate([
            'company_name'      => 'required|string|max:255',
            'position'          => 'required|string|max:255',
            'description'       => 'required|string',
            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after:start_date',
            'currently_working' => 'boolean',
            'skills'            => 'required|string',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        // Replace logo
        if ($request->hasFile('logo')) {
            if ($experience->logo) {
                try {
                    $this->supabaseStorage->delete($experience->logo);
                } catch (\Exception $e) {
                    \Log::error('Failed to delete old logo: ' . $e->getMessage());
                }
            }
            $validated['logo'] = $this->handleImageUpload($request->file('logo'), 'experiences/logo');
        }

        // Replace images
        if ($request->hasFile('images')) {
            if ($experience->images) {
                foreach ($experience->images as $oldImage) {
                    try {
                        $this->supabaseStorage->delete($oldImage);
                    } catch (\Exception $e) {
                        \Log::error('Failed to delete old image: ' . $e->getMessage());
                    }
                }
            }
            $validated['images'] = $this->handleImageUpload($request->file('images'), 'experiences/images');
        }

        // Convert skills string into array
        $validated['skills'] = array_map('trim', explode(',', $validated['skills']));

        $experience->update($validated);

        return redirect()->route('admin.services.show', $experience->service->slug)
            ->with('success', 'Experience updated successfully.');
    }

    public function destroy($serviceId, $experienceId)
    {
        $experience = Experience::findOrFail($experienceId);

        if ($experience->logo) {
            try {
                $this->supabaseStorage->delete($experience->logo);
            } catch (\Exception $e) {
                \Log::error('Failed to delete logo: ' . $e->getMessage());
            }
        }

        if ($experience->images) {
            foreach ($experience->images as $img) {
                try {
                    $this->supabaseStorage->delete($img);
                } catch (\Exception $e) {
                    \Log::error('Failed to delete image: ' . $e->getMessage());
                }
            }
        }

        $experience->delete();

        return redirect()->route('admin.services.show', $experience->service->slug)
            ->with('success', 'Experience deleted successfully.');
    }

    /**
     * Flexible image upload handler
     * - Accepts single file or array of files
     * - Resizes, optimizes, uploads to Supabase
     * - Returns string (for single) or array (for multiple)
     */
    private function handleImageUpload($files, $folder)
    {
        $manager = new ImageManager(new Driver());

        $processFile = function ($file) use ($manager, $folder) {
            $imageName = $folder . '/' . time() . '_' . preg_replace('/[^a-zA-Z0-9\.]/', '_', $file->getClientOriginalName());

            $img = $manager->read($file->getRealPath())
                           ->scale(width: 800);

            $tempPath = tempnam(sys_get_temp_dir(), 'supabase_');
            $img->toJpeg(80)->save($tempPath);

            $this->supabaseStorage->uploadImage($imageName, new \Illuminate\Http\UploadedFile(
                $tempPath,
                $imageName,
                $file->getClientMimeType(),
                null,
                true
            ));

            unlink($tempPath);

            return $imageName;
        };

        // Handle multiple files
        if (is_array($files)) {
            $paths = [];
            foreach ($files as $file) {
                $paths[] = $processFile($file);
            }
            return $paths;
        }

        // Handle single file
        return $processFile($files);
    }
}
