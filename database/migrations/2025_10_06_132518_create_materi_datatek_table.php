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
        Schema::create('materi_datatek', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('keterangan')->nullable();
            $table->enum('jenis_media', ['youtube', 'google_slides', 'google_docs'])->default('youtube');
            $table->string('url_media'); // URL embed dari YouTube, Google Slides, atau Google Docs
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0); // Untuk sorting
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
        Schema::dropIfExists('materi_datatek');
    }
};
