@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Add New Certificate to {{ $service->name }}</h1>
        <a href="{{ route('admin.services.show', $service->slug) }}" class="btn btn-secondary">
            Back to {{ $service->name }}
        </a>
    </div>

    <form action="{{ route('certificates.store', $service->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Certificate Details</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Certificate Title *</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="issuer" class="form-label">Issuer/Organization *</label>
                            <input type="text" class="form-control" id="issuer" name="issuer" value="{{ old('issuer') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="issue_date" class="form-label">Date Issued *</label>
                            <input type="date" class="form-control" id="issue_date" name="issue_date" value="{{ old('issue_date') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="expiry_date" class="form-label">Expiry Date (Optional)</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                        </div>

                        <div class="mb-3">
                            <label for="credential_id" class="form-label">Credential ID (Optional)</label>
                            <input type="text" class="form-control" id="credential_id" name="credential_id" value="{{ old('credential_id') }}">
                        </div>

                        <div class="mb-3">
                            <label for="credential_url" class="form-label">Credential URL (Optional)</label>
                            <input type="url" class="form-control" id="credential_url" name="credential_url" value="{{ old('credential_url') }}">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">Certificate File</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="certificate_file" class="form-label">Upload Certificate File *</label>
                            <input type="file" class="form-control" id="certificate_file" name="certificate_file" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                        
                        <div class="form-text">
                            Accepted formats: JPG, PNG, PDF. Max file size: 4MB.
                        </div>
                    </div>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Create Certificate</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
