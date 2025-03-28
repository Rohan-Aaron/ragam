<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Str;
use voku\helper\ASCII;
class Categories extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'categories';

    public function works()
    {
        return $this->hasMany(Works::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = $category->generateKannadaSlug($category->name);
            }
        });

        static::updating(function ($category) {
            
                $category->slug = $category->generateKannadaSlug($category->name);
            
        });
    }

    protected function generateKannadaSlug($title)
    {
        // Convert spaces to hyphens
        $slug = preg_replace('/\s+/', '-', trim($title));

        // Remove special characters (except Kannada, numbers, and hyphens)
        $slug = preg_replace('/[^ಅ-ಹಁ-ಃ0-9-]/u', '', $slug);

        // Remove multiple hyphens
        $slug = preg_replace('/-+/', '-', $slug);

        // Ensure uniqueness
        $originalSlug = $slug;
        $count = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

}
