@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $service->name }} Management</h1>
        <a href="{{ url('/admin/dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <ul class="nav nav-tabs" id="serviceTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects" type="button" role="tab">Projects</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="certificates-tab" data-bs-toggle="tab" data-bs-target="#certificates" type="button" role="tab">Certificates</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="experience-tab" data-bs-toggle="tab" data-bs-target="#experience" type="button" role="tab">Experience</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="awards-tab" data-bs-toggle="tab" data-bs-target="#awards" type="button" role="tab">Awards</button>
        </li>
    </ul>

    <div class="tab-content" id="serviceTabsContent">
        <!-- ================= Projects ================= -->
        <div class="tab-pane fade show active" id="projects" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3>Projects</h3>
                <a href="{{ route('projects.create', $service->id) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Project
                </a>
            </div>

            <div class="row">
                @forelse(($service->projects ?? []) as $project)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        @if($project->image_url)
                        <img src="{{ $project->image_url }}" class="card-img-top" alt="{{ $project->title }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $project->title }}</h5>
                            <p class="card-text">{{ Str::limit($project->short_description, 100) }}</p>
                            <div class="mb-2">
                                @foreach(($project->tech_stack ?? []) as $tech)
                                <span class="badge bg-secondary me-1">{{ $tech }}</span>
                                @endforeach
                            </div>
                            <div class="card-footer d-flex justify-content-end gap-2">
                                <a href="{{ route('projects.edit', [$service->id, $project->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('projects.destroy', [$service->id, $project->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this project?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        No projects found. <a href="{{ route('projects.create', $service->id) }}">Create your first project</a>.
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- ================= Certificates ================= -->
        <div class="tab-pane fade" id="certificates" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3>Certificates</h3>
                <a href="{{ route('certificates.create', $service->id) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Certificate
                </a>
            </div>

            <div class="row">
                @forelse(($service->certificates ?? []) as $certificate)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $certificate->title }}</h5>
                            <p class="card-text">
                                <strong>Issuer:</strong> {{ $certificate->issuer }} <br>
                                <strong>Date:</strong> {{ $certificate->date }}
                            </p>
                            @if($certificate->certificate_url)
                            <a href="{{ $certificate->certificate_url }}" target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-file-alt"></i> View Certificate
                            </a>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('certificates.edit', [$service->id, $certificate->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('certificates.destroy', [$service->id, $certificate->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this certificate?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        No certificates found. <a href="{{ route('certificates.create', $service->id) }}">Upload your first certificate</a>.
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- ================= Experience ================= -->
        <div class="tab-pane fade" id="experience" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3>Experience</h3>
                <a href="{{ route('experience.create', $service->id) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Experience
                </a>
            </div>

            <div class="row">
                @forelse(($service->experience ?? []) as $exp)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $exp->role }}</h5>
                            <p class="card-text">
                                <strong>Company:</strong> {{ $exp->company }} <br>
                                <strong>Start:</strong> {{ $exp->start_date }} <br>
                                <strong>End:</strong> {{ $exp->end_date ?? 'Present' }}
                            </p>
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('experience.edit', [$service->id, $exp->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('experience.destroy', [$service->id, $exp->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this experience?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        No experience records found. <a href="{{ route('experience.create', $service->id) }}">Add your first experience</a>.
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- ================= Awards ================= -->
        <div class="tab-pane fade" id="awards" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3>Awards</h3>
                <a href="{{ route('awards.create', $service->id) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Award
                </a>
            </div>

            <div class="row">
                @forelse(($service->awards ?? []) as $award)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $award->title }}</h5>
                            <p class="card-text">
                                <strong>Issuer:</strong> {{ $award->issuer }} <br>
                                <strong>Date:</strong> {{ $award->date }}
                            </p>
                            @if($award->certificate_url)
                            <a href="{{ $award->certificate_url }}" target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-file-alt"></i> View Certificate
                            </a>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('awards.edit', [$service->id, $award->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('awards.destroy', [$service->id, $award->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this award?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        No awards found. <a href="{{ route('awards.create', $service->id) }}">Add your first award</a>.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
