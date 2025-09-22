<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Service;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseStorageService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $certificates = $service->certificates()->get();

        return view('admin.certificates.index', compact('service', 'certificates'));
    }

    public function create($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        return view('admin.certificates.create', compact('service'));
    }

    public function store(Request $request, $serviceId)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'issuer'      => 'required|string|max:255',
            'date'        => 'required|date',
            'certificate' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['title', 'issuer', 'date']);

        if ($request->hasFile('certificate')) {
            $file = $request->file('certificate');
            $path = 'certificates/' . uniqid() . '_' . $file->getClientOriginalName();

            $url = $this->supabase->upload($path, file_get_contents($file->getRealPath()));

            if (!$url) {
                return back()->with('error', 'Certificate upload failed.');
            }

            $data['file_path'] = $url;
        }

        $service = Service::findOrFail($serviceId);
        $service->certificates()->create($data);

        return redirect()->route('admin.services.show', $service->slug)
            ->with('success', 'Certificate created successfully.');
    }

    public function edit($serviceId, Certificate $certificate)
    {
        $service = Service::findOrFail($serviceId);
        return view('admin.certificates.edit', compact('service', 'certificate'));
    }

    public function update(Request $request, $serviceId, Certificate $certificate)
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
            if ($certificate->file_path) {
                $this->supabase->delete($certificate->file_path);
            }

            $file = $request->file('certificate');
            $path = 'certificates/' . uniqid() . '_' . $file->getClientOriginalName();
            $url  = $this->supabase->upload($path, file_get_contents($file->getRealPath()));

            if (!$url) {
                return back()->with('error', 'Certificate upload failed.');
            }

            $data['file_path'] = $url;
        }

        $certificate->update($data);

        return redirect()->route('admin.services.show', $certificate->service->slug)
            ->with('success', 'Certificate updated successfully.');
    }

    public function destroy($serviceId, Certificate $certificate)
    {
        if ($certificate->file_path) {
            $this->supabase->delete($certificate->file_path);
        }

        $certificate->delete();

        return redirect()->route('admin.services.show', $certificate->service->slug)
            ->with('success', 'Certificate deleted successfully.');
    }
}
