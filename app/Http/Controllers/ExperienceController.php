<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Service;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseStorageService $supabase)
    {
        $this->supabase = $supabase;
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

    public function store(Request $request, $serviceId)
    {
        $validator = Validator::make($request->all(), [
            'company_name'      => 'required|string|max:255',
            'position'          => 'required|string|max:255',
            'description'       => 'required|string',
            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after:start_date',
            'currently_working' => 'boolean',
            'skills'            => 'required|string',
            'logo'              => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*'          => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['logo', 'images']);
        $data['skills'] = array_map('trim', explode(',', $data['skills']));

        // Logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = 'experiences/logo/' . uniqid() . '_' . $file->getClientOriginalName();

            $url = $this->supabase->upload($path, file_get_contents($file->getRealPath()));

            if (!$url) {
                return back()->with('error', 'Logo upload failed.');
            }

            $data['logo'] = $url;
        }

        // Multiple images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = 'experiences/images/' . uniqid() . '_' . $file->getClientOriginalName();
                $url  = $this->supabase->upload($path, file_get_contents($file->getRealPath()));

                if ($url) {
                    $imagePaths[] = $url;
                }
            }
            $data['images'] = $imagePaths;
        }

        $service = Service::findOrFail($serviceId);
        $service->experiences()->create($data);

        return redirect()->route('admin.services.show', $service->slug)
            ->with('success', 'Experience created successfully.');
    }

    public function edit($serviceId, Experience $experience)
    {
        $service = Service::findOrFail($serviceId);
        return view('admin.experiences.edit', compact('service', 'experience'));
    }

    public function update(Request $request, $serviceId, Experience $experience)
    {
        $validator = Validator::make($request->all(), [
            'company_name'      => 'required|string|max:255',
            'position'          => 'required|string|max:255',
            'description'       => 'required|string',
            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after:start_date',
            'currently_working' => 'boolean',
            'skills'            => 'required|string',
            'logo'              => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*'          => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['logo', 'images']);
        $data['skills'] = array_map('trim', explode(',', $data['skills']));

        // Replace logo
        if ($request->hasFile('logo')) {
            if ($experience->logo) {
                $this->supabase->delete($experience->logo);
            }

            $file = $request->file('logo');
            $path = 'experiences/logo/' . uniqid() . '_' . $file->getClientOriginalName();
            $url  = $this->supabase->upload($path, file_get_contents($file->getRealPath()));

            if (!$url) {
                return back()->with('error', 'Logo upload failed.');
            }

            $data['logo'] = $url;
        }

        // Replace images
        if ($request->hasFile('images')) {
            if ($experience->images) {
                foreach ($experience->images as $oldImage) {
                    $this->supabase->delete($oldImage);
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $file) {
                $path = 'experiences/images/' . uniqid() . '_' . $file->getClientOriginalName();
                $url  = $this->supabase->upload($path, file_get_contents($file->getRealPath()));

                if ($url) {
                    $imagePaths[] = $url;
                }
            }
            $data['images'] = $imagePaths;
        }

        $experience->update($data);

        return redirect()->route('admin.services.show', $experience->service->slug)
            ->with('success', 'Experience updated successfully.');
    }

    public function destroy($serviceId, Experience $experience)
    {
        if ($experience->logo) {
            $this->supabase->delete($experience->logo);
        }

        if ($experience->images) {
            foreach ($experience->images as $img) {
                $this->supabase->delete($img);
            }
        }

        $experience->delete();

        return redirect()->route('admin.services.show', $experience->service->slug)
            ->with('success', 'Experience deleted successfully.');
    }
}
