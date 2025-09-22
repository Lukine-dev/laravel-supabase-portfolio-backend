@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Portfolio Dashboard</h1>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-laptop-code fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Web Development</h5>
                    <p class="card-text">Manage your web development projects, certificates, experience, and awards.</p>
                    <a href="{{ route('admin.services.show', 'web-development') }}" class="btn btn-primary">Manage Web Development</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-paint-brush fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">Creatives</h5>
                    <p class="card-text">Manage your creative projects, certificates, experience, and awards.</p>
                    <a href="{{ route('admin.services.show', 'creatives') }}" class="btn btn-success">Manage Creatives</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection