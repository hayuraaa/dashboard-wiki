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
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_source')->unique(); // pendidikan, komunitas, kebudayaan, datatek
            $table->string('recipient_email'); // Email penerima utama
            $table->text('cc_emails')->nullable(); // Email CC (disimpan sebagai JSON array)
            $table->boolean('is_active')->default(true); // Aktif/nonaktif pengiriman email
            $table->boolean('send_auto_reply')->default(false); // Kirim balasan otomatis ke pengirim
            $table->text('auto_reply_message')->nullable(); // Pesan balasan otomatis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_settings');
    }
};
