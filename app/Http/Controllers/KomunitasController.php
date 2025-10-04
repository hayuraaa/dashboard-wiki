<?php

namespace App\Http\Controllers;

use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class KomunitasController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view komunitas', only: ['index', 'show']),
            new Middleware('permission:create komunitas', only: ['create', 'store']),
            new Middleware('permission:edit komunitas', only: ['edit', 'update']),
            new Middleware('permission:delete komunitas', only: ['destroy']),
        ];
    }

    public function index()
    {
        $komunitas = Komunitas::with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Komunitas/Index', [
            'komunitas' => $komunitas
        ]);
    }

    public function create()
    {
        $jenisKomunitas = ['Daerah', 'Bahasa', 'Proyek', 'KlubWiki'];

        return Inertia::render('Komunitas/Create', [
            'jenisKomunitas' => $jenisKomunitas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_komunitas' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'deskripsi' => 'nullable|string',
            'jenis_komunitas' => 'required|in:Daerah,Bahasa,Proyek,KlubWiki',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'surel' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'social_media' => 'nullable|string|max:255',
            'jumlah_anggota' => 'nullable|integer|min:0',
            'tanggal_berdiri' => 'nullable|date',
            'aktif' => 'boolean',
        ]);

        $data = $request->except('logo');
        $data['created_by'] = auth()->id();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('komunitas-logos', 'public');
            $data['logo'] = $logoPath;
        }

        $komunitas = Komunitas::create($data);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($komunitas)
            ->log('Created new komunitas');

        return redirect()->route('komunitas.index')->with('success', 'Komunitas berhasil ditambahkan.');
    }

    public function show(Komunitas $komunita)
    {
        return Inertia::render('Komunitas/Show', [
            'komunitas' => $komunita->load(['creator', 'updater'])
        ]);
    }

    public function edit(Komunitas $komunita)
    {
        $jenisKomunitas = ['Daerah', 'Bahasa', 'Proyek', 'KlubWiki'];

        return Inertia::render('Komunitas/Edit', [
            'komunitas' => $komunita,
            'jenisKomunitas' => $jenisKomunitas
        ]);
    }

    public function update(Request $request, Komunitas $komunita)
    {
        $request->validate([
            'nama_komunitas' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'deskripsi' => 'nullable|string',
            'jenis_komunitas' => 'required|in:Daerah,Bahasa,Proyek,KlubWiki',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'surel' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'social_media' => 'nullable|string|max:255',
            'jumlah_anggota' => 'nullable|integer|min:0',
            'tanggal_berdiri' => 'nullable|date',
            'aktif' => 'boolean',
        ]);

        $data = $request->except('logo');
        $data['updated_by'] = auth()->id();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($komunita->logo) {
                Storage::disk('public')->delete($komunita->logo);
            }

            $logoPath = $request->file('logo')->store('komunitas-logos', 'public');
            $data['logo'] = $logoPath;
        }

        $komunita->update($data);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($komunita)
            ->log('Updated komunitas');

        return redirect()->route('komunitas.index')->with('success', 'Komunitas berhasil diupdate.');
    }

    public function destroy(Komunitas $komunita)
    {
        // Delete logo if exists
        if ($komunita->logo) {
            Storage::disk('public')->delete($komunita->logo);
        }

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($komunita)
            ->withProperties(['deleted_komunitas' => $komunita->toArray()])
            ->log('Deleted komunitas');

        $komunita->delete();

        return redirect()->route('komunitas.index')->with('success', 'Komunitas berhasil dihapus.');
    }
}
