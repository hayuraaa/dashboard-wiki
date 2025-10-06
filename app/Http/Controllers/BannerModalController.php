<?php

namespace App\Http\Controllers;

use App\Models\BannerModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class BannerModalController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view banner modal', only: ['index', 'show']),
            new Middleware('permission:create banner modal', only: ['create', 'store']),
            new Middleware('permission:edit banner modal', only: ['edit', 'update']),
            new Middleware('permission:delete banner modal', only: ['destroy']),
        ];
    }

    public function index()
    {
        $banners = BannerModal::with(['creator', 'updater'])
            ->orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('BannerModal/Index', [
            'banners' => $banners
        ]);
    }

    public function create()
    {
        $tipeBanner = [
            ['value' => 'event', 'label' => 'Event'],
            ['value' => 'lomba', 'label' => 'Lomba'],
            ['value' => 'kegiatan', 'label' => 'Kegiatan'],
            ['value' => 'pengumuman', 'label' => 'Pengumuman'],
            ['value' => 'konferensi', 'label' => 'Konferensi'],
            ['value' => 'lainnya', 'label' => 'Lainnya'],
        ];

        $targetSitusOptions = [
            ['value' => 'datatek', 'label' => 'Data & Teknologi'],
            ['value' => 'komunitas', 'label' => 'Komunitas'],
            ['value' => 'pendidikan', 'label' => 'Pendidikan'],
            ['value' => 'kebudayaan', 'label' => 'Kebudayaan'],
        ];

        return Inertia::render('BannerModal/Create', [
            'tipeBanner' => $tipeBanner,
            'targetSitusOptions' => $targetSitusOptions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url_pranala' => 'nullable|url|max:500',
            'text_tombol' => 'nullable|string|max:100',
            'tipe' => 'required|in:event,lomba,kegiatan,pengumuman,konferensi,lainnya',
            'target_situs' => 'nullable|array',
            'is_active' => 'boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $data = $request->except('gambar');
        $data['created_by'] = auth()->id();

        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('banner-modals', 'public');
            $data['gambar'] = $gambarPath;
        }

        $banner = BannerModal::create($data);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($banner)
            ->log('Created new banner modal');

        return redirect()->route('banner-modals.index')
            ->with('success', 'Banner berhasil ditambahkan.');
    }

    public function show(BannerModal $bannerModal)
    {
        return Inertia::render('BannerModal/Show', [
            'banner' => $bannerModal->load(['creator', 'updater'])
        ]);
    }

    public function edit(BannerModal $bannerModal)
    {
        $tipeBanner = [
            ['value' => 'event', 'label' => 'Event'],
            ['value' => 'lomba', 'label' => 'Lomba'],
            ['value' => 'kegiatan', 'label' => 'Kegiatan'],
            ['value' => 'pengumuman', 'label' => 'Pengumuman'],
            ['value' => 'konferensi', 'label' => 'Konferensi'],
            ['value' => 'lainnya', 'label' => 'Lainnya'],
        ];

        $targetSitusOptions = [
            ['value' => 'datatek', 'label' => 'Data & Teknologi'],
            ['value' => 'komunitas', 'label' => 'Komunitas'],
            ['value' => 'pendidikan', 'label' => 'Pendidikan'],
            ['value' => 'kebudayaan', 'label' => 'Kebudayaan'],
        ];

        return Inertia::render('BannerModal/Edit', [
            'banner' => $bannerModal,
            'tipeBanner' => $tipeBanner,
            'targetSitusOptions' => $targetSitusOptions
        ]);
    }

    public function update(Request $request, BannerModal $bannerModal)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url_pranala' => 'nullable|url|max:500',
            'text_tombol' => 'nullable|string|max:100',
            'tipe' => 'required|in:event,lomba,kegiatan,pengumuman,konferensi,lainnya',
            'target_situs' => 'nullable|array',
            'is_active' => 'boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $data = $request->except('gambar');
        $data['updated_by'] = auth()->id();

        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            // Delete old gambar if exists
            if ($bannerModal->gambar) {
                Storage::disk('public')->delete($bannerModal->gambar);
            }

            $gambarPath = $request->file('gambar')->store('banner-modals', 'public');
            $data['gambar'] = $gambarPath;
        }

        $bannerModal->update($data);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($bannerModal)
            ->log('Updated banner modal');

        return redirect()->route('banner-modals.index')
            ->with('success', 'Banner berhasil diupdate.');
    }

    public function destroy(BannerModal $bannerModal)
    {
        // Delete gambar if exists
        if ($bannerModal->gambar) {
            Storage::disk('public')->delete($bannerModal->gambar);
        }

        // Log activity before deletion
        activity()
            ->causedBy(auth()->user())
            ->performedOn($bannerModal)
            ->withProperties(['deleted_banner' => $bannerModal->toArray()])
            ->log('Deleted banner modal');

        $bannerModal->delete();

        return redirect()->route('banner-modals.index')
            ->with('success', 'Banner berhasil dihapus.');
    }
}
