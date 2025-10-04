<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class ContactSettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view contact settings', only: ['index']),
            new Middleware('permission:edit contact settings', only: ['edit', 'update']),
        ];
    }

    public function index()
    {
        $settings = ContactSetting::all();
        
        return Inertia::render('ContactSettings/Index', [
            'settings' => $settings
        ]);
    }

    public function edit(ContactSetting $contactSetting)
    {
        return Inertia::render('ContactSettings/Edit', [
            'setting' => $contactSetting
        ]);
    }

    public function update(Request $request, ContactSetting $contactSetting)
    {
        $validated = $request->validate([
            'recipient_email' => 'required|email',
            'cc_emails' => 'nullable|array',
            'cc_emails.*' => 'email',
            'is_active' => 'required|boolean',
            'send_auto_reply' => 'required|boolean',
            'auto_reply_message' => 'nullable|string',
        ]);

        $contactSetting->update($validated);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($contactSetting)
            ->log('Updated contact settings for ' . $contactSetting->website_source);

        return redirect()->route('contact-settings.index')
            ->with('success', 'Pengaturan kontak berhasil diperbarui');
    }
}