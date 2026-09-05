<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    // Relasi: Satu Kategori punya Banyak Foto (Galeri)
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}