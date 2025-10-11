<?php

namespace App\Http\Controllers;

use App\Models\MateriDatatek;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class MediaKebudayaanController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view materi kebudayaan', only: ['index', 'show']),
            new Middleware('permission:create materi kebudayaan', only: ['create', 'store']),
            new Middleware('permission:edit materi kebudayaan', only: ['edit', 'update']),
            new Middleware('permission:delete materi kebudayaan', only: ['destroy']),
        ];
    }

    public function index()
    {
        $materi = MateriDatatek::with(['creator', 'updater'])
            ->where('kategori', 'kebudayaan') // Filter hanya kategori kebudayaan
            ->orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('MediaKebudayaan/Index', [
            'materi' => $materi
        ]);
    }

    public function create()
    {
        $jenisMedia = [
            ['value' => 'youtube', 'label' => 'YouTube'],
            ['value' => 'google_slides', 'label' => 'Google Slides'],
            ['value' => 'google_docs', 'label' => 'Google Docs'],
            ['value' => 'wikimedia_commons', 'label' => 'Wikimedia Commons'],
        ];

        return Inertia::render('MediaKebudayaan/Create', [
            'jenisMedia' => $jenisMedia
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'jenis_media' => 'required|in:youtube,google_slides,google_docs,wikimedia_commons',
            'url_media' => 'required|url|max:500',
            'is_active' => 'boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['kategori'] = 'kebudayaan'; // Set kategori kebudayaan

        $materi = MateriDatatek::create($data);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($materi)
            ->log('Created new media kebudayaan');

        return redirect()->route('media-kebudayaan.index')
            ->with('success', 'Media kebudayaan berhasil ditambahkan.');
    }

    public function show(MateriDatatek $mediaKebudayaan)
    {
        return Inertia::render('MediaKebudayaan/Show', [
            'materi' => $mediaKebudayaan->load(['creator', 'updater'])
        ]);
    }

    public function edit(MateriDatatek $mediaKebudayaan)
    {
        $jenisMedia = [
            ['value' => 'youtube', 'label' => 'YouTube'],
            ['value' => 'google_slides', 'label' => 'Google Slides'],
            ['value' => 'google_docs', 'label' => 'Google Docs'],
            ['value' => 'wikimedia_commons', 'label' => 'Wikimedia Commons'],
        ];

        return Inertia::render('MediaKebudayaan/Edit', [
            'materi' => $mediaKebudayaan,
            'jenisMedia' => $jenisMedia
        ]);
    }

    public function update(Request $request, MateriDatatek $mediaKebudayaan)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'jenis_media' => 'required|in:youtube,google_slides,google_docs,wikimedia_commons',
            'url_media' => 'required|url|max:500',
            'is_active' => 'boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['updated_by'] = auth()->id();

        $mediaKebudayaan->update($data);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($mediaKebudayaan)
            ->log('Updated media kebudayaan');

        return redirect()->route('media-kebudayaan.index')
            ->with('success', 'Media kebudayaan berhasil diupdate.');
    }

    public function destroy(MateriDatatek $mediaKebudayaan)
    {
        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($mediaKebudayaan)
            ->withProperties(['deleted_materi' => $mediaKebudayaan->toArray()])
            ->log('Deleted media kebudayaan');

        $mediaKebudayaan->delete();

        return redirect()->route('media-kebudayaan.index')
            ->with('success', 'Media kebudayaan berhasil dihapus.');
    }
}