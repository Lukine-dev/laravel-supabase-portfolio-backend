<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'title', 'issuer', 'date_issued', 'credential_id',
        'certificate_image', 'description', 'credential_file'
    ];

    protected $casts = [
        'date_issued' => 'date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getCertificateImageUrlAttribute()
    {
        return $this->certificate_image ? Storage::disk('supabase')->url($this->certificate_image) : null;
    }

    public function getCredentialFileUrlAttribute()
    {
        return $this->credential_file ? Storage::disk('supabase')->url($this->credential_file) : null;
    }
}