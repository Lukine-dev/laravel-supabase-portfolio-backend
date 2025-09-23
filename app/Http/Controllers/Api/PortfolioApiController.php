<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use App\Models\Project;
use App\Models\Experience;
use App\Models\Certificate;
use App\Models\Award;
use App\Services\SupabaseStorageService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PortfolioApiController extends Controller
{
    protected $supabaseStorage;

    public function __construct()
    {
        $this->supabaseStorage = new SupabaseStorageService();
    }

    /**
     * Get all portfolio data (all services with related items)
     */
    public function getFullPortfolio(): JsonResponse
    {
        $services = Service::with(['projects', 'experiences', 'certificates', 'awards'])->get();

        return response()->json([
            'success' => true,
            'data' => $services->map(fn($s) => $this->formatServiceData($s))
        ]);
    }

    /**
     * Get portfolio for a specific service by ID or slug
     */
    public function getServicePortfolio($serviceId): JsonResponse
    {
        try {
            $service = Service::with(['projects', 'experiences', 'certificates', 'awards'])
                ->where('id', $serviceId)
                ->orWhere('slug', $serviceId)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $this->formatServiceData($service)
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Service not found'], 404);
        }
    }

    /**
     * Get all services
     */
    public function getServices(): JsonResponse
    {
        $services = Service::all();

        return response()->json([
            'success' => true,
            'data' => $services->map(fn($s) => $this->formatServiceData($s))
        ]);
    }

    /**
     * Get projects for a service
     */
    public function getProjects($serviceId): JsonResponse
    {
        try {
            $service = Service::where('id', $serviceId)
                ->orWhere('slug', $serviceId)
                ->firstOrFail();

            $projects = $service->projects()
                ->orderBy('start_date', 'desc')
                ->get()
                ->map(fn($p) => $this->formatProjectData($p));

            return response()->json(['success' => true, 'data' => $projects]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Service not found'], 404);
        }
    }

    /**
     * Get experiences for a service
     */
    public function getExperiences($serviceId): JsonResponse
    {
        try {
            $service = Service::where('id', $serviceId)
                ->orWhere('slug', $serviceId)
                ->firstOrFail();

            $experiences = $service->experiences()
                ->orderBy('start_date', 'desc')
                ->get()
                ->map(fn($e) => $this->formatExperienceData($e));

            return response()->json(['success' => true, 'data' => $experiences]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Service not found'], 404);
        }
    }

    /**
     * Get certificates for a service
     */
    public function getCertificates($serviceId): JsonResponse
    {
        try {
            $service = Service::where('id', $serviceId)
                ->orWhere('slug', $serviceId)
                ->firstOrFail();

            $certificates = $service->certificates()
                ->orderBy('date', 'desc')
                ->get()
                ->map(fn($c) => $this->formatCertificateData($c));

            return response()->json(['success' => true, 'data' => $certificates]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Service not found'], 404);
        }
    }

    /**
     * Get awards for a service
     */
    public function getAwards($serviceId): JsonResponse
    {
        try {
            $service = Service::where('id', $serviceId)
                ->orWhere('slug', $serviceId)
                ->firstOrFail();

            $awards = $service->awards()
                ->orderBy('date', 'desc')
                ->get()
                ->map(fn($a) => $this->formatAwardData($a));

            return response()->json(['success' => true, 'data' => $awards]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Service not found'], 404);
        }
    }

    /**
     * Get a single project
     */
    public function getProject($id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            return response()->json(['success' => true, 'data' => $this->formatProjectData($project)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Project not found'], 404);
        }
    }

    /**
     * Get a single experience
     */
    public function getExperience($id): JsonResponse
    {
        try {
            $experience = Experience::findOrFail($id);
            return response()->json(['success' => true, 'data' => $this->formatExperienceData($experience)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Experience not found'], 404);
        }
    }

    /**
     * Get a single certificate
     */
    public function getCertificate($id): JsonResponse
    {
        try {
            $certificate = Certificate::findOrFail($id);
            return response()->json(['success' => true, 'data' => $this->formatCertificateData($certificate)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Certificate not found'], 404);
        }
    }

    /**
     * Get a single award
     */
    public function getAward($id): JsonResponse
    {
        try {
            $award = Award::findOrFail($id);
            return response()->json(['success' => true, 'data' => $this->formatAwardData($award)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Award not found'], 404);
        }
    }

    // -------------------------
    // format helpers
    // -------------------------

    protected function formatServiceData(Service $service): array
    {
        return [
            'id' => $service->id,
            'name' => $service->name,
            'slug' => $service->slug,
            'projects' => $service->projects->map(fn($p) => $this->formatProjectData($p)),
            'experiences' => $service->experiences->map(fn($e) => $this->formatExperienceData($e)),
            'certificates' => $service->certificates->map(fn($c) => $this->formatCertificateData($c)),
            'awards' => $service->awards->map(fn($a) => $this->formatAwardData($a)),
        ];
    }

    protected function formatProjectData(Project $project): array
    {
        return [
            'id' => $project->id,
            'title' => $project->title,
            'description' => $project->description,
            'start_date' => $project->start_date,
            'end_date' => $project->end_date,
            'image_url' => $project->image_url
                ? $this->supabaseStorage->getPublicUrl('projects', $project->image_url)
                : null,
        ];
    }

    protected function formatExperienceData(Experience $experience): array
    {
        return [
            'id' => $experience->id,
            'role' => $experience->role,
            'company' => $experience->company,
            'start_date' => $experience->start_date,
            'end_date' => $experience->end_date,
            'description' => $experience->description,
            'logo_url' => $experience->logo_url
                ? $this->supabaseStorage->getPublicUrl('experiences', $experience->logo_url)
                : null,
        ];
    }

    protected function formatCertificateData(Certificate $certificate): array
    {
        return [
            'id' => $certificate->id,
            'title' => $certificate->title,
            'issuer' => $certificate->issuer,
            'date' => $certificate->date,
            'file_url' => $certificate->file_url
                ? $this->supabaseStorage->getPublicUrl('certificates', $certificate->file_url)
                : null,
        ];
    }

    protected function formatAwardData(Award $award): array
    {
        return [
            'id' => $award->id,
            'title' => $award->title,
            'issuer' => $award->issuer,
            'date' => $award->date,
            'description' => $award->description,
            'image_url' => $award->image_url
                ? $this->supabaseStorage->getPublicUrl('awards', $award->image_url)
                : null,
        ];
    }
}
