<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\SliderCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class SliderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view sliders', only: ['index', 'show']),
            new Middleware('permission:create sliders', only: ['create', 'store']),
            new Middleware('permission:edit sliders', only: ['edit', 'update']),
            new Middleware('permission:delete sliders', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::with('sliderCategory')
            ->orderBy('urutan')
            ->latest()
            ->get();

        return Inertia::render('Slider/Index', [
            'sliders' => $sliders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = SliderCategory::active()->get();

        return Inertia::render('Slider/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slider_category_id' => 'required|exists:slider_categories,id',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Upload gambar
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('sliders', 'public');
            $validated['gambar'] = $gambarPath;
        }

        $slider = Slider::create($validated);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($slider)
            ->log('Created new slider');

        return redirect()->route('sliders.index')
            ->with('success', 'Slider berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        $categories = SliderCategory::active()->get();

        return Inertia::render('Slider/Edit', [
            'slider' => $slider->load('sliderCategory'),
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'slider_category_id' => 'required|exists:slider_categories,id',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Handle gambar upload - HANYA update jika ada file baru
        if ($request->hasFile('gambar')) {
            // Delete old gambar
            if ($slider->gambar) {
                Storage::disk('public')->delete($slider->gambar);
            }

            $gambarPath = $request->file('gambar')->store('sliders', 'public');
            $validated['gambar'] = $gambarPath;
        } else {
            // PENTING: Hapus key 'gambar' dari validated jika tidak ada file baru
            unset($validated['gambar']);
        }

        $slider->update($validated);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($slider)
            ->log('Updated slider');

        return redirect()->route('sliders.index')
            ->with('success', 'Slider berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        // Delete gambar
        if ($slider->gambar) {
            Storage::disk('public')->delete($slider->gambar);
        }

        // Log before delete
        activity()
            ->causedBy(auth()->user())
            ->performedOn($slider)
            ->withProperties(['deleted_slider' => $slider->toArray()])
            ->log('Deleted slider');

        $slider->delete();

        return redirect()->route('sliders.index')
            ->with('success', 'Slider berhasil dihapus');
    }
}