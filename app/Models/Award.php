<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'title', 'image', 'description', 'date_issued'
    ];

    protected $casts = [
        'date_issued' => 'date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::disk('supabase')->url($this->image) : null;
    }
}