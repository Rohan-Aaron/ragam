<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Works extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    protected $table = 'works';
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($work) {
            // Generate slug if it is empty
            if (empty($work->slug)) {
                $work->slug = $work->generateKannadaSlug($work->title);
            }
        });

        static::updating(function ($work) {
            // Update the slug when the title changes
            $work->slug = $work->generateKannadaSlug($work->title);
        });
    }

    protected function generateKannadaSlug($title)
    {
        // Convert spaces to hyphens
        $slug = preg_replace('/\s+/', '-', trim($title));

        // Remove special characters (except Kannada, numbers, and hyphens)
        $slug = preg_replace('/[^ಅ-ಹಁ-ಃa-zA-Z0-9-]/u', '', $slug); // Allow Kannada and English letters, numbers, and hyphens

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

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }


}
