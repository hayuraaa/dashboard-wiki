<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class ContactSubmissionAdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view contact submissions', only: ['index', 'show']),
            new Middleware('permission:edit contact submissions', only: ['updateStatus']),
            new Middleware('permission:delete contact submissions', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = ContactSubmission::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by website
        if ($request->has('website') && $request->website !== 'all') {
            $query->where('website_source', $request->website);
        }

        $submissions = $query->latest()->get();

        return Inertia::render('ContactSubmissions/Index', [
            'submissions' => $submissions,
            'filters' => [
                'status' => $request->status ?? 'all',
                'website' => $request->website ?? 'all',
            ],
            'stats' => [
                'total' => ContactSubmission::count(),
                'unread' => ContactSubmission::where('status', 'unread')->count(),
                'read' => ContactSubmission::where('status', 'read')->count(),
                'replied' => ContactSubmission::where('status', 'replied')->count(),
            ]
        ]);
    }

    public function show(ContactSubmission $contactSubmission)
    {
        // Tandai sebagai read jika masih unread
        if ($contactSubmission->status === 'unread') {
            $contactSubmission->update(['status' => 'read']);
            
            activity()
                ->causedBy(auth()->user())
                ->performedOn($contactSubmission)
                ->log('Marked contact submission as read');
        }

        return Inertia::render('ContactSubmissions/Show', [
            'submission' => $contactSubmission
        ]);
    }

    public function updateStatus(Request $request, ContactSubmission $contactSubmission)
    {
        $validated = $request->validate([
            'status' => 'required|in:unread,read,replied'
        ]);

        $contactSubmission->update($validated);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($contactSubmission)
            ->log('Updated contact submission status to ' . $validated['status']);

        return back()->with('success', 'Status berhasil diperbarui');
    }

    public function destroy(ContactSubmission $contactSubmission)
    {
        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($contactSubmission)
            ->withProperties(['deleted_submission' => $contactSubmission->toArray()])
            ->log('Deleted contact submission');

        $contactSubmission->delete();

        return redirect()->route('contact-submissions.index')
            ->with('success', 'Pesan kontak berhasil dihapus');
    }
}