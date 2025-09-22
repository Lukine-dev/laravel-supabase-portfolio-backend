@extends('layouts.app')

@section('content')
<style>
    :root {
        --theme-color: #90143c;
        --theme-light: #fce6ec;
    }

    .text-theme {
        color: var(--theme-color);
    }

    .btn-theme {
        background-color: var(--theme-color);
        color: white;
        transition: all 0.3s ease;
    }
    .btn-theme:hover {
        background-color: #6d0f2e;
        color: white;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .card-header {
        background-color: var(--theme-light);
        color: var(--theme-color);
        font-weight: 600;
        border-radius: 12px 12px 0 0;
        font-size: 1.1rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--theme-color);
    }

    input.form-control, 
    textarea.form-control, 
    select.form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }
    input.form-control:focus, 
    textarea.form-control:focus, 
    select.form-control:focus {
        border-color: var(--theme-color);
        box-shadow: 0 0 0 0.2rem rgba(144, 20, 60, 0.2);
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-theme">Add New Award to {{ $service->name }}</h1>
        <a href="{{ route('admin.services.show', $service->slug) }}" class="btn btn-secondary shadow-sm">
            Back to {{ $service->name }}
        </a>
    </div>

    <form action="{{ route('awards.store', $service->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Award Details</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Award Title *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="issuer" class="form-label">Issuer *</label>
                            <input type="text" class="form-control" id="issuer" name="issuer" 
                                   value="{{ old('issuer') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date Awarded *</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="{{ old('date') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">Certificate Upload</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="certificate" class="form-label">Upload Certificate (Optional)</label>
                            <input type="file" class="form-control" id="certificate" name="certificate" 
                                   accept=".jpg,.jpeg,.png,.pdf">
                        </div>

                        <div class="form-text">
                            Accepted formats: JPG, PNG, PDF. Max file size: 4MB.
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-theme btn-lg shadow-sm">
                        Create Award
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
