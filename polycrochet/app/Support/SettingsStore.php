<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SettingsStore
{
    /**
     * Cache key for storing all settings.
     */
    protected const CACHE_KEY = 'app.settings.all';

    /**
     * Retrieve all settings as an array.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::query()
                ->orderBy('key')
                ->get()
                ->mapWithKeys(function (Setting $setting) {
                    return [$setting->key => $this->decodeValue($setting->value)];
                })
                ->all();
        });
    }

    /**
     * Retrieve a setting value.
     *
     * @template T
     *
     * @param  T|null  $default
     * @return T|mixed|null
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->all(), $key, $default);
    }

    /**
     * Persist one or many settings.
     *
     * @param  array<string, mixed>  $values
     */
    public function set(array $values): void
    {
        Collection::make($values)
            ->each(function (mixed $value, string $key) {
                if ($value === null) {
                    Setting::query()->where('key', $key)->delete();

                    return;
                }

                Setting::query()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $this->encodeValue($value)]
                );
            });

        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Remove cached settings.
     */
    public function refresh(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    protected function encodeValue(mixed $value): array|null
    {
        if ($value === null) {
            return null;
        }

        return ['data' => $value];
    }

    protected function decodeValue(mixed $value): mixed
    {
        if (is_array($value) && array_key_exists('data', $value)) {
            return $value['data'];
        }

        return $value;
    }
}
