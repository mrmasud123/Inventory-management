<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'description',
        'thumbnail_path',
    ];


    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];


    // helper for full url
    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail_path) {
            return null;
        }


        return \Illuminate\Support\Facades\Storage::url($this->thumbnail_path);
    }
}
