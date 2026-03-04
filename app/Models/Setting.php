<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key', 'value', 'type', 'group', 'updated_by',
    ];

    /**
     * If you want caching, set SETTINGS_CACHE=true in .env
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $useCache = config('app.settings_cache', false);

        if ($useCache) {
            $cacheKey = "setting:{$key}";
            return Cache::rememberForever($cacheKey, function () use ($key, $default) {
                $row = static::query()->where('key', $key)->first();
                return $row ? static::castOut($row->value, $row->type) : $default;
            });
        }

        $row = static::query()->where('key', $key)->first();
        return $row ? static::castOut($row->value, $row->type) : $default;
    }

    public static function set(
        string $key,
        mixed $value,
        string $type = 'string',
        string $group = 'general',
        ?int $updatedBy = null
    ): self {
        $stored = static::castIn($value, $type);

        $row = static::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => $stored,
                'type' => $type,
                'group' => $group,
                'updated_by' => $updatedBy,
            ]
        );

        if (config('app.settings_cache', false)) {
            Cache::forget("setting:{$key}");
        }

        return $row;
    }

    /**
     * Cast from DB string to usable PHP value
     */
    protected static function castOut(?string $value, string $type): mixed
    {
        if ($value === null) return null;

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? false,
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Cast from PHP value to DB string
     */
    protected static function castIn(mixed $value, string $type): ?string
    {
        if ($value === null) return null;

        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) ((int) $value),
            'json' => json_encode($value, JSON_UNESCAPED_UNICODE),
            default => (string) $value,
        };
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    
}