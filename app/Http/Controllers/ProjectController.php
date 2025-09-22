<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Services\SupabaseStorageService;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // or Imagick if available

class ProjectController extends Controller
{
    protected $supabaseStorage;

    public function __construct()
    {
        $this->supabaseStorage = new SupabaseStorageService();
    }

    public function index($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $projects = $service->projects()->get();

        return view('admin.projects.index', compact('service', 'projects'));
    }

    public function create($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        return view('admin.projects.create', compact('service'));
    }

    public function edit($serviceId, $projectId)
    {
        $service = Service::findOrFail($serviceId);
        $project = Project::findOrFail($projectId);

        return view('admin.projects.edit', compact('service', 'project'));
    }

    public function store(Request $request, $serviceId)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description'  => 'required|string',
            'start_date'        => 'required|date',
            'completion_date'   => 'nullable|date|after:start_date',
            'position'          => 'required|string|max:255',
            'tech_stack'        => 'required|string',
            'github_url'        => 'nullable|url',
            'project_url'       => 'nullable|url',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $this->handleImageUpload($request->file('image'), 'projects');
        }

        // Convert tech stack to array
        $validated['tech_stack'] = array_map('trim', explode(',', $validated['tech_stack']));

        $service = Service::findOrFail($serviceId);
        $service->projects()->create($validated);

        return redirect()->route('admin.services.show', $service->slug)
            ->with('success', 'Project created successfully.');
    }

    public function update(Request $request, $serviceId, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description'  => 'required|string',
            'start_date'        => 'required|date',
            'completion_date'   => 'nullable|date|after:start_date',
            'position'          => 'required|string|max:255',
            'tech_stack'        => 'required|string',
            'github_url'        => 'nullable|url',
            'project_url'       => 'nullable|url',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($project->image_path) {
                try {
                    $this->supabaseStorage->delete($project->image_path);
                } catch (\Exception $e) {
                    \Log::error('Failed to delete old image: ' . $e->getMessage());
                }
            }

            $validated['image_path'] = $this->handleImageUpload($request->file('image'), 'projects');
        }

        // Convert tech stack to array
        $validated['tech_stack'] = array_map('trim', explode(',', $validated['tech_stack']));

        $project->update($validated);

        return redirect()->route('admin.services.show', $project->service->slug)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy($serviceId, $projectId)
    {
        $project = Project::findOrFail($projectId);

        if ($project->image_path) {
            try {
                $this->supabaseStorage->delete($project->image_path);
            } catch (\Exception $e) {
                \Log::error('Failed to delete image: ' . $e->getMessage());
            }
        }

        $project->delete();

        return redirect()->route('admin.services.show', $project->service->slug)
            ->with('success', 'Project deleted successfully.');
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
