<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public const STATUS_PENDING    = 'pending';
    public const STATUS_REGISTERED = 'registered';

    protected $table = 'staff';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'address',
        'profile_picture',
        'password',
        'is_active',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /** Accepted accounts (the ones shown in Staff Management / allowed to log in). */
    public function scopeRegistered($query)
    {
        return $query->where('status', self::STATUS_REGISTERED);
    }

    /** Self-registered accounts still waiting for admin approval. */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Accessor for the staff member's full name.
     */
    protected function fullName(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}",
        );
    }
}