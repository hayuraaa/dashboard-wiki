<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('komunitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komunitas');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('jenis_komunitas', ['Daerah', 'Bahasa', 'Proyek', 'KlubWiki']);
            $table->string('logo')->nullable(); // path to logo file
            $table->string('surel')->nullable(); // email komunitas
            $table->string('website')->nullable(); // website komunitas
            $table->string('telepon')->nullable(); // nomor telepon
            $table->string('alamat')->nullable(); // alamat lengkap
            $table->string('social_media')->nullable(); // link social media
            $table->integer('jumlah_anggota')->default(0)->nullable(); // jumlah anggota
            $table->date('tanggal_berdiri')->nullable(); // tanggal berdiri komunitas
            $table->boolean('aktif')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komunitas');
    }
};
