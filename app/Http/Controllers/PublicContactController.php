<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\ContactSetting;
use App\Mail\ContactSubmissionMail;
use App\Mail\ContactAutoReplyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PublicContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/ContactForms');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'website_source' => 'required|in:pendidikan,komunitas,kebudayaan,datatek',
        ]);

        // Simpan ke database
        $submission = ContactSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'website_source' => $validated['website_source'],
            'status' => 'unread',
        ]);

        // Ambil pengaturan email untuk website ini
        $setting = ContactSetting::where('website_source', $validated['website_source'])->first();

        if ($setting && $setting->is_active) {
            try {
                // Kirim email ke admin/recipient
                Mail::to($setting->recipient_email)
                    ->cc($setting->cc_emails ?? [])
                    ->send(new ContactSubmissionMail($submission));

                // Kirim auto reply jika aktif
                if ($setting->send_auto_reply && $setting->auto_reply_message) {
                    Mail::to($validated['email'])
                        ->send(new ContactAutoReplyMail($submission, $setting->auto_reply_message));
                }
            } catch (\Exception $e) {
                \Log::error('Email sending failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Pesan Anda telah berhasil dikirim!');
    }
}