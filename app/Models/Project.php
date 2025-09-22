<?php

// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\SupabaseStorageService;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'title', 'short_description', 'full_description',
        'start_date', 'completion_date', 'position', 'tech_stack',
        'github_url', 'project_url', 'image_path'
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'start_date' => 'date',
        'completion_date' => 'date',
    ];

    protected $appends = ['image_url'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }
        
        $supabaseStorage = new SupabaseStorageService();
        return $supabaseStorage->getPublicUrl($this->image_path);
    }
}