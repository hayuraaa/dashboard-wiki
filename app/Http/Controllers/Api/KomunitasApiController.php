<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KomunitasApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Komunitas::with(['creator', 'updater']);

            // Filter berdasarkan jenis komunitas
            if ($request->has('jenis_komunitas') && $request->jenis_komunitas) {
                $query->where('jenis_komunitas', $request->jenis_komunitas);
            }

            // Filter berdasarkan status aktif
            if ($request->has('aktif')) {
                $query->where('aktif', $request->boolean('aktif'));
            }

            // Search berdasarkan nama
            if ($request->has('search') && $request->search) {
                $query->where('nama_komunitas', 'like', '%' . $request->search . '%');
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            if ($request->has('paginate') && $request->boolean('paginate')) {
                $komunitas = $query->orderBy('created_at', 'desc')->paginate($perPage);
            } else {
                $komunitas = $query->orderBy('created_at', 'desc')->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data komunitas berhasil diambil',
                'data' => $komunitas
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data komunitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
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

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->except('logo');

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('komunitas-logos', 'public');
                $data['logo'] = $logoPath;
            }

            $komunitas = Komunitas::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Komunitas berhasil ditambahkan',
                'data' => $komunitas->load(['creator', 'updater'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan komunitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $komunitas = Komunitas::with(['creator', 'updater'])->find($id);

            if (!$komunitas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komunitas tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail komunitas berhasil diambil',
                'data' => $komunitas
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail komunitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $komunitas = Komunitas::find($id);

            if (!$komunitas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komunitas tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
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

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->except('logo');

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($komunitas->logo) {
                    Storage::disk('public')->delete($komunitas->logo);
                }

                $logoPath = $request->file('logo')->store('komunitas-logos', 'public');
                $data['logo'] = $logoPath;
            }

            $komunitas->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Komunitas berhasil diupdate',
                'data' => $komunitas->load(['creator', 'updater'])
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate komunitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $komunitas = Komunitas::find($id);

            if (!$komunitas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komunitas tidak ditemukan'
                ], 404);
            }

            // Delete logo if exists
            if ($komunitas->logo) {
                Storage::disk('public')->delete($komunitas->logo);
            }

            $komunitas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Komunitas berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus komunitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get jenis komunitas options
     */
    public function getJenisKomunitas()
    {
        $jenisKomunitas = ['Daerah', 'Bahasa', 'Proyek', 'KlubWiki'];

        return response()->json([
            'success' => true,
            'message' => 'Jenis komunitas berhasil diambil',
            'data' => $jenisKomunitas
        ], 200);
    }

    /**
     * Get komunitas statistics
     */
    public function getStatistics()
    {
        try {
            $statistics = [
                'total_komunitas' => Komunitas::count(),
                'komunitas_aktif' => Komunitas::where('aktif', true)->count(),
                'komunitas_tidak_aktif' => Komunitas::where('aktif', false)->count(),
                'by_jenis' => [
                    'Daerah' => Komunitas::where('jenis_komunitas', 'Daerah')->count(),
                    'Bahasa' => Komunitas::where('jenis_komunitas', 'Bahasa')->count(),
                    'Proyek' => Komunitas::where('jenis_komunitas', 'Proyek')->count(),
                    'KlubWiki' => Komunitas::where('jenis_komunitas', 'KlubWiki')->count(),
                ],
                'total_anggota' => Komunitas::sum('jumlah_anggota')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Statistik komunitas berhasil diambil',
                'data' => $statistics
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik komunitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}