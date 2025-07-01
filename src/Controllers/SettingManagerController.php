<?php

namespace admin\settings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\settings\Requests\SettingCreateRequest;
use admin\settings\Requests\SettingUpdateRequest;
use admin\settings\Models\Setting;

class SettingManagerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $settings = Setting::
                filter($request->query('keyword'))
                ->latest()
                ->paginate(5)
                ->withQueryString();

            return view('setting::admin.index', compact('settings'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load settings: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('setting::admin.createOrEdit');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load settings: ' . $e->getMessage());
        }
    }

    public function store(SettingCreateRequest $request)
    {
        try {
            $requestData = $request->validated();

            Setting::create($requestData);
            return redirect()->route('admin.settings.index')->with('success', 'setting created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load settings: ' . $e->getMessage());
        }
    }

    /**
     * show setting details
     */
    public function show(Setting $setting)
    {
        try {
            return view('setting::admin.show', compact('setting'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load settings: ' . $e->getMessage());
        }
    }

    public function edit(Setting $setting)
    {
        try {
            return view('setting::admin.createOrEdit', compact('setting'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load setting for editing: ' . $e->getMessage());
        }
    }

    public function update(SettingUpdateRequest $request, Setting $setting)
    {
        try {
            $requestData = $request->validated();

            $setting->update($requestData);
            return redirect()->route('admin.settings.index')->with('success', 'Setting updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load setting for editing: ' . $e->getMessage());
        }
    }

    public function destroy(Setting $setting)
    {
        try {
            $setting->delete();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }
}

