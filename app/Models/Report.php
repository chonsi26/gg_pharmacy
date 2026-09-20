<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'report_title',
        'report_type',
        'report_date',
        'status',
        'description',
        'remarks',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    /**
     * Available status values, handy for validation / dropdowns.
     */
    public const STATUSES = [
        'pending',
        'completed',
        'cancelled',
    ];

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}