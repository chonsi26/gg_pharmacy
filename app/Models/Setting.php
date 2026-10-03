<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * The pharmacy settings panel defines exactly 22 distinct keys
     * (see admin.pharmacy's field list, now including `map_link`), so this
     * table should never legitimately hold more than 22 rows. Anything from
     * row 23 onward is stale/junk data and gets trimmed automatically.
     *
     * NOTE: bump this whenever a new field is added to the settings panel,
     * otherwise enforceRowLimit() will delete the newest key.
     */
    public const MAX_ROWS = 22;

    /** Settings key holding the Google Maps embed URL of the pharmacy. */
    public const MAP_LINK_KEY = 'map_link';

    public static function get(string $key, mixed $default = null): mixed
    {
        // oldest('id') ensures that if duplicate keys ever exist, the first
        // one ever created is treated as the authoritative value.
        return static::where('key', $key)->oldest('id')->value('value') ?? $default;
    }

    /**
     * The pharmacy's Google Maps embed URL, ready to drop into an
     * <iframe src="...">. Accepts either a bare URL or a full pasted
     * <iframe> snippet, and returns null unless the result is a genuine
     * https Google Maps embed URL (so nothing arbitrary is ever framed).
     */
    public static function mapEmbedUrl(): ?string
    {
        $raw = trim((string) static::get(self::MAP_LINK_KEY, ''));

        if ($raw === '') {
            return null;
        }

        if (stripos($raw, '<iframe') !== false
            && preg_match('/\bsrc\s*=\s*["\']([^"\']+)["\']/i', $raw, $m)) {
            $raw = $m[1];
        }

        $url   = html_entity_decode(trim($raw), ENT_QUOTES);
        $parts = parse_url($url);

        if (!$parts || ($parts['scheme'] ?? '') !== 'https') {
            return null;
        }

        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';
        parse_str($parts['query'] ?? '', $query);

        $isGoogle = in_array($host, ['www.google.com', 'google.com', 'maps.google.com'], true);
        $isEmbed  = str_starts_with($path, '/maps/embed') || ($query['output'] ?? '') === 'embed';

        return ($isGoogle && $isEmbed) ? $url : null;
    }

    public static function allAsArray(): array
    {
        // Built manually (rather than pluck('value', 'key')) so that if a
        // duplicate key somehow exists, the earliest (lowest id) row wins —
        // pluck() would let the LAST row with that key overwrite the array
        // entry, which is the opposite of what we want.
        $result = [];

        foreach (static::orderBy('id')->get(['key', 'value']) as $setting) {
            if (!array_key_exists($setting->key, $result)) {
                $result[$setting->key] = $setting->value;
            }
        }

        return $result;
    }

    /**
     * Remove duplicate settings rows, keeping only the earliest (lowest id)
     * row for each key and deleting every later duplicate. Safe to call
     * repeatedly — it's a no-op once there are no duplicates left.
     */
    public static function dedupe(): int
    {
        $duplicateKeys = static::query()
            ->select('key')
            ->groupBy('key')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('key');

        if ($duplicateKeys->isEmpty()) {
            return 0;
        }

        $deleted = 0;

        foreach ($duplicateKeys as $key) {
            $idsToDelete = static::where('key', $key)
                ->orderBy('id')
                ->pluck('id')
                ->skip(1) // keep the first (lowest id) row
                ->values();

            if ($idsToDelete->isNotEmpty()) {
                $deleted += static::whereIn('id', $idsToDelete)->delete();
            }
        }

        return $deleted;
    }

    /**
     * Cap the table at MAX_ROWS rows. Keeps the earliest MAX_ROWS rows
     * (lowest id) and deletes the 22nd row and everything after it.
     * Safe to call repeatedly — it's a no-op once the table is at or
     * under the limit. Call dedupe() first so genuine duplicates are
     * cleared before this blunter row-count cap runs.
     */
    public static function enforceRowLimit(int $limit = self::MAX_ROWS): int
    {
        $total = static::count();

        if ($total <= $limit) {
            return 0;
        }

        $idsToDelete = static::orderBy('id')
            ->pluck('id')
            ->skip($limit)
            ->values();

        return $idsToDelete->isEmpty() ? 0 : static::whereIn('id', $idsToDelete)->delete();
    }
}