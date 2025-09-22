@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Add New Award to {{ $service->name }}</h1>
        <a href="{{ route('admin.services.show', $service->slug) }}" class="btn btn-secondary">
            Back to {{ $service->name }}
        </a>
    </div>

    <form action="{{ route('awards.store', $service->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Award Details</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Award Title *</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="issuer" class="form-label">Issuer *</label>
                            <input type="text" class="form-control" id="issuer" name="issuer" value="{{ old('issuer') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date Awarded *</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">Certificate Upload</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="certificate" class="form-label">Upload Certificate (Optional)</label>
                            <input type="file" class="form-control" id="certificate" name="certificate" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                        
                        <div class="form-text">
                            Accepted formats: JPG, PNG, PDF. Max file size: 4MB.
                        </div>
                    </div>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Create Award</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
