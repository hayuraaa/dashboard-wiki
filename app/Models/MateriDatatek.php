<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MateriDatatek extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'materi_datatek';

    protected $fillable = [
        'judul',
        'keterangan',
        'jenis_media',
        'kategori',
        'url_media',
        'is_active',
        'urutan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Get the user who created the materi.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the materi.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['judul', 'keterangan', 'jenis_media', 'url_media', 'is_active', 'urutan'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Convert YouTube URL to embed URL
     */
    public function getEmbedUrlAttribute()
    {
        if ($this->jenis_media === 'wikimedia_commons') {
            // Ambil URL PDF langsung
            if (preg_match('/File:(.+\.pdf)/i', $this->url_media, $matches)) {
                $fileName = str_replace(' ', '_', $matches[1]);
                return "https://upload.wikimedia.org/wikipedia/commons/" . 
                    $this->getWikimediaPath($fileName);
            }
        }

        if ($this->jenis_media === 'youtube') {
            // Convert various YouTube URL formats to embed format
            $url = $this->url_media;
            
            // Check if already embed URL
            if (strpos($url, 'youtube.com/embed/') !== false) {
                return $url;
            }
            
            // Extract video ID from various formats
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }
        
        return $this->url_media;
    }

    private function getWikimediaPath($fileName)
    {
        // Hash untuk path Wikimedia
        $hash = md5($fileName);
        return substr($hash, 0, 1) . '/' . substr($hash, 0, 2) . '/' . $fileName;
    }

}