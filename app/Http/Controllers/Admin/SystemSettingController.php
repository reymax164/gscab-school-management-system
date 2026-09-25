<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all();

        return view('admin.system_settings.index', compact('settings'));
    }

    public function create()
    {
        return view('admin.system_settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:100|unique:system_settings',
            'value' => 'required|string',
        ]);

        SystemSetting::create($validated);

        return redirect()->route('admin.system-settings.index')
            ->with('success', 'Setting created successfully.');
    }

    public function edit(SystemSetting $systemSetting)
    {
        return view('admin.system_settings.edit', compact('systemSetting'));
    }

    public function update(Request $request, SystemSetting $systemSetting)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:100|unique:system_settings,key,'.$systemSetting->id,
            'value' => 'required|string',
        ]);

        $systemSetting->update($validated);

        return redirect()->route('admin.system-settings.index')
            ->with('success', 'Setting updated successfully.');
    }

    public function destroy(SystemSetting $systemSetting)
    {
        $systemSetting->delete();

        return redirect()->route('admin.system-settings.index')
            ->with('success', 'Setting deleted successfully.');
    }
}
