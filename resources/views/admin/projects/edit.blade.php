@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Project for {{ $service->name }}</h1>
        <a href="{{ route('admin.services.show', $service->slug) }}" class="btn btn-secondary">
            Back to {{ $service->name }}
        </a>
    </div>

    <form action="{{ route('projects.update', [$service->id, $project->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Project Details</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Project Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title', $project->title) }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description *</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror"
                                id="short_description" name="short_description" rows="3" required>{{ old('short_description', $project->short_description) }}</textarea>
                            @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="full_description" class="form-label">Full Description *</label>
                            <textarea class="form-control @error('full_description') is-invalid @enderror"
                                id="full_description" name="full_description" rows="5" required>{{ old('full_description', $project->full_description) }}</textarea>
                            @error('full_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date" 
                                        value="{{ old('start_date', $project->start_date) }}" required>
                                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="completion_date" class="form-label">Completion Date</label>
                                    <input type="date" class="form-control @error('completion_date') is-invalid @enderror"
                                        id="completion_date" name="completion_date" 
                                        value="{{ old('completion_date', $project->completion_date) }}">
                                    @error('completion_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="position" class="form-label">Your Position/Role *</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror"
                                id="position" name="position" 
                                value="{{ old('position', $project->position) }}" required>
                            @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="tech_stack" class="form-label">Tech Stack (comma separated) *</label>
                            <input type="text" class="form-control @error('tech_stack') is-invalid @enderror"
                                id="tech_stack" name="tech_stack" 
                                value="{{ old('tech_stack', is_array($project->tech_stack) ? implode(', ', $project->tech_stack) : $project->tech_stack) }}" required>
                            <div class="form-text">Example: Laravel, Bootstrap, PostgreSQL, Supabase</div>
                            @error('tech_stack') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="github_url" class="form-label">GitHub URL</label>
                                    <input type="url" class="form-control @error('github_url') is-invalid @enderror"
                                        id="github_url" name="github_url" 
                                        value="{{ old('github_url', $project->github_url) }}">
                                    @error('github_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="project_url" class="form-label">Live Project URL</label>
                                    <input type="url" class="form-control @error('project_url') is-invalid @enderror"
                                        id="project_url" name="project_url" 
                                        value="{{ old('project_url', $project->project_url) }}">
                                    @error('project_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">Project Image</div>
                    <div class="card-body">
                        @if($project->image)
                            <div class="mb-2">
                                <img src="{{ $project->image_url ?? $project->image }}" 
                                    alt="Current Project Image" class="img-fluid rounded shadow" style="max-height: 200px;">
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="image" class="form-label">Replace Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                id="image" name="image" accept="image/*">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="form-text">
                            Recommended size: 800x600px. Max file size: 2MB.
                        </div>
                    </div>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Update Project</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
