<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A row in the staff portal's notification bell (see StaffNotificationsController).
 * Mirrors App\Models\Notification, but backed by the `staff_notifications` table.
 */
class StaffNotification extends Model
{
    use HasFactory;

    protected $table = 'staff_notifications';

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

    /** Still needs attention (important AND not yet handled). */
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
