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
        <div class="tab-pane fade show active" id="projects" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3>Projects</h3>
                <a href="{{ route('projects.create', $service->id) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Project
                </a>
            </div>
            
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            
            <div class="row">
                @forelse($service->projects as $project)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        @if($project->image_url)
                        <img src="{{ $project->image_url }}" class="card-img-top" alt="{{ $project->title }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $project->title }}</h5>
                            <p class="card-text">{{ Str::limit($project->short_description, 100) }}</p>
                            <div class="mb-2">
                                @foreach($project->tech_stack as $tech)
                                <span class="badge bg-secondary me-1">{{ $tech }}</span>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-between">
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
        
        <!-- Similar content for certificates, experience, and awards tabs -->
    </div>
</div>
@endsection