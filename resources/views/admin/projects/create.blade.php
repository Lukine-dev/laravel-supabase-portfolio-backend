@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Add New Project to {{ $service->name }}</h1>
        <a href="{{ route('admin.services.show', $service->slug) }}" class="btn btn-secondary">Back to {{ $service->name }}</a>
    </div>

    <form action="{{ route('projects.store', $service->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Project Details</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Project Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description *</label>
                            <textarea class="form-control" id="short_description" name="short_description" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="full_description" class="form-label">Full Description *</label>
                            <textarea class="form-control" id="full_description" name="full_description" rows="5" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="completion_date" class="form-label">Completion Date</label>
                                    <input type="date" class="form-control" id="completion_date" name="completion_date">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="position" class="form-label">Your Position/Role *</label>
                            <input type="text" class="form-control" id="position" name="position" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tech_stack" class="form-label">Tech Stack (comma separated) *</label>
                            <input type="text" class="form-control" id="tech_stack" name="tech_stack" required>
                            <div class="form-text">Example: Laravel, Bootstrap, PostgreSQL, Supabase</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="github_url" class="form-label">GitHub URL</label>
                                    <input type="url" class="form-control" id="github_url" name="github_url">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="project_url" class="form-label">Live Project URL</label>
                                    <input type="url" class="form-control" id="project_url" name="project_url">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">Project Image</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="image" class="form-label">Project Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        
                        <div class="form-text">
                            Recommended size: 800x600px. Max file size: 2MB.
                        </div>
                    </div>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Create Project</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection