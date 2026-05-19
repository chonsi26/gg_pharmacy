<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FullWidthBanner extends Model
{
    protected $fillable = ['section_key', 'image', 'alt', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public static function forSection(string $key): ?self
    {
        return static::where('section_key', $key)
                     ->where('is_active', true)
                     ->first();
    }
}
