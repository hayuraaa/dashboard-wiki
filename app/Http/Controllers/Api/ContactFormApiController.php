<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\ContactSetting;
use App\Mail\ContactSubmissionMail;
use App\Mail\ContactAutoReplyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactFormApiController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'website_source' => 'required|in:pendidikan,komunitas,kebudayaan,datatek',
        ]);

        $validated['status'] = 'unread';

        // Simpan ke database
        $submission = ContactSubmission::create($validated);

        // Ambil setting
        $setting = ContactSetting::where('website_source', $submission->website_source)
            ->where('is_active', true)
            ->first();

        if ($setting) {
            // Kirim email ke recipient
            Mail::to($setting->recipient_email)
                ->send(new ContactSubmissionMail($submission));

            // Kirim ke CC
            if (!empty($setting->cc_emails)) {
                Mail::to($setting->cc_emails)
                    ->send(new ContactSubmissionMail($submission));
            }

            // Auto reply
            if ($setting->send_auto_reply && !empty($setting->auto_reply_message)) {
                Mail::to($submission->email)
                    ->send(new ContactAutoReplyMail($submission, $setting->auto_reply_message));
            }
        }

        return response()->json(['success' => true, 'message' => 'Pesan berhasil dikirim'], 200);
    }
}