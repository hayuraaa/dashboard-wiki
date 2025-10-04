<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ContactSetting;
use Illuminate\Database\Seeder;

class ContactSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'website_source' => 'pendidikan',
                'recipient_email' => 'pendidikan@wikimedia.or.id',
                'cc_emails' => ['admin@wikimedia.or.id'],
                'is_active' => true,
                'send_auto_reply' => true,
                'auto_reply_message' => 'Terima kasih telah menghubungi Portal Pendidikan Wikimedia Indonesia. Pesan Anda telah kami terima dan akan segera kami proses.',
            ],
            [
                'website_source' => 'komunitas',
                'recipient_email' => 'komunitas@wikimedia.or.id',
                'cc_emails' => ['admin@wikimedia.or.id'],
                'is_active' => true,
                'send_auto_reply' => true,
                'auto_reply_message' => 'Terima kasih telah menghubungi Portal Komunitas Wikimedia Indonesia. Pesan Anda telah kami terima dan akan segera kami proses.',
            ],
            [
                'website_source' => 'kebudayaan',
                'recipient_email' => 'kebudayaan@wikimedia.or.id',
                'cc_emails' => ['admin@wikimedia.or.id'],
                'is_active' => true,
                'send_auto_reply' => true,
                'auto_reply_message' => 'Terima kasih telah menghubungi Portal Kebudayaan Wikimedia Indonesia. Pesan Anda telah kami terima dan akan segera kami proses.',
            ],
            [
                'website_source' => 'datatek',
                'recipient_email' => 'datatek@wikimedia.or.id',
                'cc_emails' => ['admin@wikimedia.or.id'],
                'is_active' => true,
                'send_auto_reply' => true,
                'auto_reply_message' => 'Terima kasih telah menghubungi Portal DataTek Wikimedia Indonesia. Pesan Anda telah kami terima dan akan segera kami proses.',
            ],
        ];

        foreach ($settings as $setting) {
            ContactSetting::updateOrCreate(
                ['website_source' => $setting['website_source']],
                $setting
            );
        }
    }
}
