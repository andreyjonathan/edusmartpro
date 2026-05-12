<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Setting;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(Request $request)
    {
        // Get settings grouped by their group
        $settings = Setting::all()->groupBy('group')->mapWithKeys(function ($group, $key) {
            return [$key => $group->pluck('value', 'key')];
        });

        return Inertia::render('Settings/Index', [
            'settings' => $settings,
            'tab' => $request->get('tab', 'school')
        ]);
    }

    public function update(Request $request)
    {
        $this->settingService->updateSettings($request->all());

        return redirect()->route('settings.index')->with('message', 'Settings updated successfully.');
    }
}
