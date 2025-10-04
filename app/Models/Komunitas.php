<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Komunitas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'komunitas';

    protected $fillable = [
        'nama_komunitas',
        'latitude',
        'longitude',
        'deskripsi',
        'jenis_komunitas',
        'logo',
        'surel',
        'website',
        'telepon',
        'alamat',
        'social_media',
        'jumlah_anggota',
        'tanggal_berdiri',
        'aktif',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'tanggal_berdiri' => 'date',
        'jumlah_anggota' => 'integer',
    ];

    // Relasi ke User (yang membuat)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke User (yang update)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
