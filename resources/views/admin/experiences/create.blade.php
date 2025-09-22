@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Add New Experience to {{ $service->name }}</h1>
        <a href="{{ route('admin.services.show', $service->slug) }}" class="btn btn-secondary">
            Back to {{ $service->name }}
        </a>
    </div>

    <form action="{{ route('experiences.store', $service->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Experience Details</div>
                    <div class="card-body">
                        
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name *</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position *</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror"
                                id="position" name="position" value="{{ old('position') }}" required>
                            @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    id="end_date" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="currently_working" 
                                name="currently_working" value="1" {{ old('currently_working') ? 'checked' : '' }}>
                            <label class="form-check-label" for="currently_working">
                                Currently Working Here
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="skills" class="form-label">Skills (comma-separated) *</label>
                            <input type="text" class="form-control @error('skills') is-invalid @enderror"
                                id="skills" name="skills" value="{{ old('skills') }}" required>
                            <div class="form-text">Example: Laravel, Vue.js, MySQL</div>
                            @error('skills') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">Uploads</div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="logo" class="form-label">Company Logo (Optional)</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                id="logo" name="logo" accept=".jpg,.jpeg,.png,.gif,.webp">
                            <div class="form-text">Max 2MB. JPG, PNG, GIF, WEBP.</div>
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Supporting Images (Optional)</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror"
                                id="images" name="images[]" multiple accept=".jpg,.jpeg,.png,.gif,.webp">
                            <div class="form-text">You can upload multiple images. Max 4MB each.</div>
                            @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Create Experience</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
