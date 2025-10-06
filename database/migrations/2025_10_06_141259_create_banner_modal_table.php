<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_modals', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable(); // Path ke gambar banner
            $table->string('url_pranala')->nullable(); // URL tujuan
            $table->string('text_tombol')->nullable(); // Text tombol (contoh: "Daftar Sekarang", "Selengkapnya")
            
            // Pengaturan tampilan
            $table->enum('tipe', ['event', 'lomba', 'kegiatan', 'pengumuman', 'konferensi', 'lainnya'])->default('pengumuman');
            $table->integer('urutan')->default(0); // Untuk sorting jika ada multiple banner
        
            // Pengaturan target
            $table->json('target_situs')->nullable(); // Array situs mana saja yang menampilkan banner ini
            // Contoh: ["datatek", "komunitas", "pendidikan", "kebudayaan"]
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_modals');
    }
};