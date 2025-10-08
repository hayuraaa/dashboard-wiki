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
        Schema::table('materi_datatek', function (Blueprint $table) {
            DB::statement("ALTER TABLE materi_datatek MODIFY COLUMN jenis_media ENUM('youtube', 'google_slides', 'google_docs', 'wikimedia_commons') DEFAULT 'youtube'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi_datatek', function (Blueprint $table) {
            DB::statement("ALTER TABLE materi_datatek MODIFY COLUMN jenis_media ENUM('youtube', 'google_slides', 'google_docs') DEFAULT 'youtube'");
        });
    }
};
