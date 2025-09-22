<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\AwardController;

// Authentication routes
Auth::routes();

// Admin routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Services
    Route::get('/services/{serviceSlug}', [AdminController::class, 'showService'])->name('admin.services.show');
    
    // Projects
    Route::get('services/{serviceId}/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('services/{serviceId}/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('services/{serviceId}/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('services/{serviceId}/projects/{projectId}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('services/{serviceId}/projects/{projectId}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('services/{serviceId}/projects/{projectId}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    
    // Certificates
    Route::get('services/{serviceId}/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('services/{serviceId}/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
    Route::post('services/{serviceId}/certificates', [CertificateController::class, 'store'])->name('certificates.store');
    Route::get('services/{serviceId}/certificates/{certificateId}/edit', [CertificateController::class, 'edit'])->name('certificates.edit');
    Route::put('services/{serviceId}/certificates/{certificateId}', [CertificateController::class, 'update'])->name('certificates.update');
    Route::delete('services/{serviceId}/certificates/{certificateId}', [CertificateController::class, 'destroy'])->name('certificates.destroy');
    
    // Experience
    Route::get('services/{serviceId}/experience', [ExperienceController::class, 'index'])->name('experience.index');
    Route::get('services/{serviceId}/experience/create', [ExperienceController::class, 'create'])->name('experience.create');
    Route::post('services/{serviceId}/experience', [ExperienceController::class, 'store'])->name('experience.store');
    Route::get('services/{serviceId}/experience/{experienceId}/edit', [ExperienceController::class, 'edit'])->name('experience.edit');
    Route::put('services/{serviceId}/experience/{experienceId}', [ExperienceController::class, 'update'])->name('experience.update');
    Route::delete('services/{serviceId}/experience/{experienceId}', [ExperienceController::class, 'destroy'])->name('experience.destroy');
    
    // Awards
    Route::get('services/{serviceId}/awards', [AwardController::class, 'index'])->name('awards.index');
    Route::get('services/{serviceId}/awards/create', [AwardController::class, 'create'])->name('awards.create');
    Route::post('services/{serviceId}/awards', [AwardController::class, 'store'])->name('awards.store');
    Route::get('services/{serviceId}/awards/{awardId}/edit', [AwardController::class, 'edit'])->name('awards.edit');
    Route::put('services/{serviceId}/awards/{awardId}', [AwardController::class, 'update'])->name('awards.update');
    Route::delete('services/{serviceId}/awards/{awardId}', [AwardController::class, 'destroy'])->name('awards.destroy');
});

// Home route
Route::get('/', function () {
    return redirect('/admin/dashboard');
});

// routes/web.php
Route::get('/test-supabase', function () {
    try {
        // Test database connection
        DB::connection()->getPdo();
        echo "Database connection successful!<br>";
        
        // Test Supabase storage
        $supabase = new App\Services\SupabaseStorageService();
        $url = $supabase->getPublicUrl('test.txt');
        echo "Supabase storage URL generation successful!<br>";
        echo "Public URL would be: " . $url . "<br>";
        
        // Test a simple upload
        $testContent = "Hello Supabase! " . now();
        $path = 'test-' . time() . '.txt';
        
        try {
            $supabase->upload($path, $testContent, 'text/plain');
            echo "File upload successful!<br>";
            
            // Try to get the public URL
            $publicUrl = $supabase->getPublicUrl($path);
            echo "Uploaded file URL: <a href='$publicUrl' target='_blank'>$publicUrl</a><br>";
            
            // Test delete
            $supabase->delete($path);
            echo "File delete successful!<br>";
            
        } catch (\Exception $e) {
            echo "File operation error: " . $e->getMessage() . "<br>";
        }
        
        echo "All tests completed!";
        
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
    }
});