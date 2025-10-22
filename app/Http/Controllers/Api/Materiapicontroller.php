<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MateriDatatek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MateriApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = MateriDatatek::with(['creator', 'updater']);

            // Filter berdasarkan kategori
            if ($request->has('kategori') && $request->kategori) {
                $query->where('kategori', $request->kategori);
            }

            // Filter berdasarkan jenis media
            if ($request->has('jenis_media') && $request->jenis_media) {
                $query->where('jenis_media', $request->jenis_media);
            }

            // Filter berdasarkan status aktif
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            // Search berdasarkan judul
            if ($request->has('search') && $request->search) {
                $query->where('judul', 'like', '%' . $request->search . '%');
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            if ($request->has('paginate') && $request->boolean('paginate')) {
                $materi = $query->orderBy('urutan', 'asc')->orderBy('created_at', 'desc')->paginate($perPage);
            } else {
                $materi = $query->orderBy('urutan', 'asc')->orderBy('created_at', 'desc')->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data materi berhasil diambil',
                'data' => $materi
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data materi',
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
                'judul' => 'required|string|max:255',
                'keterangan' => 'nullable|string',
                'jenis_media' => 'required|in:youtube,google_slides,google_docs,wikimedia_commons',
                'kategori' => 'required|in:datatek,kebudayaan',
                'url_media' => 'required|url|max:500',
                'is_active' => 'boolean',
                'urutan' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            $data['created_by'] = auth()->id();

            $materi = MateriDatatek::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Materi berhasil ditambahkan',
                'data' => $materi->load(['creator', 'updater'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan materi',
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
            $materi = MateriDatatek::with(['creator', 'updater'])->find($id);

            if (!$materi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Materi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail materi berhasil diambil',
                'data' => $materi
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail materi',
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
            $materi = MateriDatatek::find($id);

            if (!$materi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Materi tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:255',
                'keterangan' => 'nullable|string',
                'jenis_media' => 'required|in:youtube,google_slides,google_docs,wikimedia_commons',
                'kategori' => 'required|in:datatek,kebudayaan',
                'url_media' => 'required|url|max:500',
                'is_active' => 'boolean',
                'urutan' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            $data['updated_by'] = auth()->id();

            $materi->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Materi berhasil diupdate',
                'data' => $materi->load(['creator', 'updater'])
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate materi',
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
            $materi = MateriDatatek::find($id);

            if (!$materi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Materi tidak ditemukan'
                ], 404);
            }

            $materi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Materi berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus materi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get kategori options
     */
    public function getKategori()
    {
        $kategori = ['datatek', 'kebudayaan'];

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diambil',
            'data' => $kategori
        ], 200);
    }

    /**
     * Get jenis media options
     */
    public function getJenisMedia()
    {
        $jenisMedia = ['youtube', 'google_slides', 'google_docs', 'wikimedia_commons'];

        return response()->json([
            'success' => true,
            'message' => 'Jenis media berhasil diambil',
            'data' => $jenisMedia
        ], 200);
    }

    /**
     * Get materi statistics
     */
    public function getStatistics()
    {
        try {
            $statistics = [
                'total_materi' => MateriDatatek::count(),
                'materi_aktif' => MateriDatatek::where('is_active', true)->count(),
                'materi_tidak_aktif' => MateriDatatek::where('is_active', false)->count(),
                'by_kategori' => [
                    'datatek' => MateriDatatek::where('kategori', 'datatek')->count(),
                    'kebudayaan' => MateriDatatek::where('kategori', 'kebudayaan')->count(),
                ],
                'by_jenis_media' => [
                    'youtube' => MateriDatatek::where('jenis_media', 'youtube')->count(),
                    'google_slides' => MateriDatatek::where('jenis_media', 'google_slides')->count(),
                    'google_docs' => MateriDatatek::where('jenis_media', 'google_docs')->count(),
                    'wikimedia_commons' => MateriDatatek::where('jenis_media', 'wikimedia_commons')->count(),
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'Statistik materi berhasil diambil',
                'data' => $statistics
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik materi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}