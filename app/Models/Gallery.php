<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['gallery_category_id', 'title', 'image_path', 'description'];

    // Relasi: Foto ini masuk ke Kategori apa?
    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }
}