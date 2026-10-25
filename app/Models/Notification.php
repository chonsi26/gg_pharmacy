<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_key',
        'type',
        'title',
        'message',
        'link',
        'is_read',
        'priority',
        'resolved_at',
    ];

    protected $casts = [
        'is_read'     => 'boolean',
        'priority'    => 'integer',
        'resolved_at' => 'datetime',
    ];

    /** Still needs the admin's attention (important AND not yet handled). */
    public function scopeImportant($query)
    {
        return $query->where('priority', '>', 0)->whereNull('resolved_at');
    }

    /** Everything that is still showing in the feed (i.e. not auto-resolved). */
    public function scopeActive($query)
    {
        return $query->whereNull('resolved_at');
    }
}