<?php

namespace App\Http\Controllers;

use App\Models\SliderCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class SliderCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view slider categories', only: ['index', 'show']),
            new Middleware('permission:create slider categories', only: ['create', 'store']),
            new Middleware('permission:edit slider categories', only: ['edit', 'update']),
            new Middleware('permission:delete slider categories', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = SliderCategory::withCount('sliders')
            ->latest()
            ->get();

        return Inertia::render('DataMaster/SliderCategory/Index', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('DataMaster/SliderCategory/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $category = SliderCategory::create($validated);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($category)
            ->log('Created new slider category');

        return redirect()->route('slider-categories.index')
            ->with('success', 'Kategori slider berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SliderCategory $sliderCategory)
    {
        return Inertia::render('DataMaster/SliderCategory/Edit', [
            'category' => $sliderCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SliderCategory $sliderCategory)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $sliderCategory->update($validated);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($sliderCategory)
            ->log('Updated slider category');

        return redirect()->route('slider-categories.index')
            ->with('success', 'Kategori slider berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SliderCategory $sliderCategory)
    {
        // Log before delete
        activity()
            ->causedBy(auth()->user())
            ->performedOn($sliderCategory)
            ->withProperties(['deleted_category' => $sliderCategory->toArray()])
            ->log('Deleted slider category');

        $sliderCategory->delete();

        return redirect()->route('slider-categories.index')
            ->with('success', 'Kategori slider berhasil dihapus');
    }
}