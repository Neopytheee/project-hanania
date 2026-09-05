<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // 1. Tampilkan Halaman Galeri
    public function index()
    {
        $categories = GalleryCategory::latest()->get();
        $galleries = Gallery::with('category')->latest()->get(); // Tarik foto beserta nama kategorinya
        
        return view('admin.galleries.index', compact('categories', 'galleries'));
    }

    // 2. Simpan Kategori Baru
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:gallery_categories,name',
        ]);

        GalleryCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Otomatis mengubah "Hotel Makkah" jadi "hotel-makkah"
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    // 3. Upload Foto Galeri
    public function store(Request $request)
    {
        $request->validate([
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'title' => 'required|string|max:255',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Maksimal 5MB
        ]);

        // Proses simpan file foto ke folder storage/app/public/galleries
        $imagePath = $request->file('image_path')->store('galleries', 'public');

        Gallery::create([
            'gallery_category_id' => $request->gallery_category_id,
            'title' => $request->title,
            'image_path' => $imagePath,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Foto berhasil diunggah ke Galeri!');
    }

    // 4. Hapus Foto
    public function destroy(Gallery $gallery)
    {
        // Hapus file fisik dari penyimpanan server
        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        // Hapus data dari database
        $gallery->delete();

        return back()->with('success', 'Foto berhasil dihapus!');
    }
}