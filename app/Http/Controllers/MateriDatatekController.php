<?php

namespace App\Http\Controllers;

use App\Models\MateriDatatek;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class MateriDatatekController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view materi', only: ['index', 'show']),
            new Middleware('permission:create materi', only: ['create', 'store']),
            new Middleware('permission:edit materi', only: ['edit', 'update']),
            new Middleware('permission:delete materi', only: ['destroy']),
        ];
    }

    public function index()
    {
        $materi = MateriDatatek::with(['creator', 'updater'])
            ->orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('MateriDatatek/Index', [
            'materi' => $materi
        ]);
    }

    public function create()
    {
        $jenisMedia = [
            ['value' => 'youtube', 'label' => 'YouTube'],
            ['value' => 'google_slides', 'label' => 'Google Slides'],
            ['value' => 'google_docs', 'label' => 'Google Docs'],
        ];

        return Inertia::render('MateriDatatek/Create', [
            'jenisMedia' => $jenisMedia
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'jenis_media' => 'required|in:youtube,google_slides,google_docs',
            'url_media' => 'required|url|max:500',
            'is_active' => 'boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();

        $materi = MateriDatatek::create($data);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($materi)
            ->log('Created new materi');

        return redirect()->route('materi-datatek.index')
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function show(MateriDatatek $materiDatatek)
    {
        return Inertia::render('MateriDatatek/Show', [
            'materi' => $materiDatatek->load(['creator', 'updater'])
        ]);
    }

    public function edit(MateriDatatek $materiDatatek)
    {
        $jenisMedia = [
            ['value' => 'youtube', 'label' => 'YouTube'],
            ['value' => 'google_slides', 'label' => 'Google Slides'],
            ['value' => 'google_docs', 'label' => 'Google Docs'],
        ];

        return Inertia::render('MateriDatatek/Edit', [
            'materi' => $materiDatatek,
            'jenisMedia' => $jenisMedia
        ]);
    }

    public function update(Request $request, MateriDatatek $materiDatatek)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'jenis_media' => 'required|in:youtube,google_slides,google_docs',
            'url_media' => 'required|url|max:500',
            'is_active' => 'boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['updated_by'] = auth()->id();

        $materiDatatek->update($data);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($materiDatatek)
            ->log('Updated materi');

        return redirect()->route('materi-datatek.index')
            ->with('success', 'Materi berhasil diupdate.');
    }

    public function destroy(MateriDatatek $materiDatatek)
    {
        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($materiDatatek)
            ->withProperties(['deleted_materi' => $materiDatatek->toArray()])
            ->log('Deleted materi');

        $materiDatatek->delete();

        return redirect()->route('materi-datatek.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}
