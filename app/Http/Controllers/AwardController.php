<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Service;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AwardController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseStorageService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $awards = $service->awards()->get();

        return view('admin.awards.index', compact('service', 'awards'));
    }

    public function create($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        return view('admin.awards.create', compact('service'));
    }

    public function store(Request $request, $serviceId)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'issuer'      => 'required|string|max:255',
            'date'        => 'required|date',
            'certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['title', 'issuer', 'date']);

        if ($request->hasFile('certificate')) {
            $file = $request->file('certificate');
            $path = 'awards/' . uniqid() . '_' . $file->getClientOriginalName();

            $url = $this->supabase->upload($path, file_get_contents($file->getRealPath()));

            if (!$url) {
                return back()->with('error', 'Certificate upload failed.');
            }

            $data['file_path'] = $url;
        }

        $service = Service::findOrFail($serviceId);
        $service->awards()->create($data);

        return redirect()->route('admin.services.show', $service->slug)
            ->with('success', 'Award created successfully.');
    }

    public function edit($serviceId, Award $award)
    {
        $service = Service::findOrFail($serviceId);
        return view('admin.awards.edit', compact('service', 'award'));
    }

    public function update(Request $request, $serviceId, Award $award)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'issuer'      => 'required|string|max:255',
            'date'        => 'required|date',
            'certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['title', 'issuer', 'date']);

        if ($request->hasFile('certificate')) {
            if ($award->file_path) {
                $this->supabase->delete($award->file_path);
            }

            $file = $request->file('certificate');
            $path = 'awards/' . uniqid() . '_' . $file->getClientOriginalName();
            $url  = $this->supabase->upload($path, file_get_contents($file->getRealPath()));

            if (!$url) {
                return back()->with('error', 'Certificate upload failed.');
            }

            $data['file_path'] = $url;
        }

        $award->update($data);

        return redirect()->route('admin.services.show', $award->service->slug)
            ->with('success', 'Award updated successfully.');
    }

    public function destroy($serviceId, Award $award)
    {
        if ($award->file_path) {
            $this->supabase->delete($award->file_path);
        }

        $award->delete();

        return redirect()->route('admin.services.show', $award->service->slug)
            ->with('success', 'Award deleted successfully.');
    }
}
