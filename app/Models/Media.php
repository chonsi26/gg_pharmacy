<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    public const TYPE_PHOTO = 'photo';
    public const TYPE_VIDEO = 'video';

    // "media" is already plural, so Laravel would try to pluralise it again
    // (or guess the wrong name) — pin the table name explicitly.
    protected $table = 'media';

    protected $fillable = ['type', 'file', 'title', 'sort_order', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopePhotos($query)
    {
        return $query->where('type', self::TYPE_PHOTO);
    }

    public function scopeVideos($query)
    {
        return $query->where('type', self::TYPE_VIDEO);
    }

    public function isVideo(): bool
    {
        return $this->type === self::TYPE_VIDEO;
    }
}
