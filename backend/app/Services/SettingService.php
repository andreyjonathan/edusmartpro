<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingService
{
    public function getAllSettings(): Collection
    {
        return Setting::all();
    }

    public function getSettingsByGroup(string $group): Collection
    {
        return Setting::where('group', $group)->get();
    }

    public function updateSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => is_array($value) ? json_encode($value) : $value]);
            }
        }
    }
}
