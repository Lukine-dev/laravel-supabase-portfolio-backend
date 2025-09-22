<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'company_name', 'position', 'description',
        'start_date', 'end_date', 'currently_working', 'skills',
        'logo', 'images'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'currently_working' => 'boolean',
        'skills' => 'array',
        'images' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? Storage::disk('supabase')->url($this->logo) : null;
    }

    public function getImageUrlsAttribute()
    {
        if (!$this->images) return null;
        
        return array_map(function($image) {
            return Storage::disk('supabase')->url($image);
        }, $this->images);
    }
}