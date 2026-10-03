<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Financial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'type',
        'category',
        'description',
        'amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Record type constants (matches the values used by the front-end).
     */
    public const TYPE_INCOME  = 'Income';
    public const TYPE_EXPENSE = 'Expense';

    /**
     * Scope a query to only include income records.
     */
    public function scopeIncome(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_INCOME);
    }

    /**
     * Scope a query to only include expense records.
     */
    public function scopeExpense(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_EXPENSE);
    }

    /**
     * Scope a query to filter by type ('all' passes through unfiltered).
     */
    public function scopeOfType(Builder $query, ?string $type): Builder
    {
        if (! $type || $type === 'all') {
            return $query;
        }

        return $query->where('type', $type);
    }

    /**
     * Scope a query to filter by category ('all' passes through unfiltered).
     */
    public function scopeOfCategory(Builder $query, ?string $category): Builder
    {
        if (! $category || $category === 'all') {
            return $query;
        }

        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by an exact date.
     */
    public function scopeOnDate(Builder $query, ?string $date): Builder
    {
        if (! $date) {
            return $query;
        }

        return $query->whereDate('date', $date);
    }

    /**
     * Scope a query to search description/category, mirroring the page's search box.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('description', 'like', "%{$term}%")
              ->orWhere('category', 'like', "%{$term}%");
        });
    }

    /**
     * Default ordering used by the page: newest date first, then newest id.
     */
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('date')->orderByDesc('id');
    }

    /**
     * Formatted amount as Philippine peso, e.g. "₱5,000.00".
     */
    public function getFormattedAmountAttribute(): string
    {
        return '₱' . number_format((float) $this->amount, 2);
    }

    /**
     * Signed, formatted amount (prefixed with + for income, - for expense).
     */
    public function getSignedAmountAttribute(): string
    {
        $sign = $this->type === self::TYPE_INCOME ? '+' : '-';

        return $sign . $this->formatted_amount;
    }
}
