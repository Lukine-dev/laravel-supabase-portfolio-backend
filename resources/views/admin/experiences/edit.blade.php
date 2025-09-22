@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Experience for {{ $service->name }}</h1>
        <a href="{{ route('admin.services.show', $service->slug) }}" class="btn btn-secondary">
            Back to {{ $service->name }}
        </a>
    </div>

    <form action="{{ route('experiences.update', [$service->id, $experience->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Experience Details</div>
                    <div class="card-body">
                        
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name *</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                id="company_name" name="company_name" 
                                value="{{ old('company_name', $experience->company_name) }}" required>
                            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position *</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror"
                                id="position" name="position" 
                                value="{{ old('position', $experience->position) }}" required>
                            @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="5" required>{{ old('description', $experience->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    id="start_date" name="start_date" 
                                    value="{{ old('start_date', $experience->start_date) }}" required>
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    id="end_date" name="end_date" 
                                    value="{{ old('end_date', $experience->end_date) }}">
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="currently_working" 
                                name="currently_working" value="1" 
                                {{ old('currently_working', $experience->currently_working) ? 'checked' : '' }}>
                            <label class="form-check-label" for="currently_working">
                                Currently Working Here
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="skills" class="form-label">Skills (comma-separated) *</label>
                            <input type="text" class="form-control @error('skills') is-invalid @enderror"
                                id="skills" name="skills" 
                                value="{{ old('skills', is_array($experience->skills) ? implode(', ', $experience->skills) : $experience->skills) }}" required>
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
                            <label for="logo" class="form-label">Company Logo</label>
                            @if($experience->logo)
                                <div class="mb-2">
                                    <img src="{{ $experience->logo_url ?? $experience->logo }}" 
                                        alt="Current Logo" class="img-fluid rounded shadow" style="max-height: 120px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                id="logo" name="logo" accept=".jpg,.jpeg,.png,.gif,.webp">
                            <div class="form-text">Max 2MB. Upload to replace current logo.</div>
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Supporting Images</label>
                            @if($experience->images && is_array($experience->images))
                                <div class="mb-2 d-flex flex-wrap gap-2">
                                    @foreach($experience->images as $img)
                                        <img src="{{ $img_url ?? $img }}" 
                                            alt="Experience Image" class="img-thumbnail" style="max-height: 100px;">
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" class="form-control @error('images') is-invalid @enderror"
                                id="images" name="images[]" multiple accept=".jpg,.jpeg,.png,.gif,.webp">
                            <div class="form-text">Upload to replace existing images. Max 4MB each.</div>
                            @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Update Experience</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
