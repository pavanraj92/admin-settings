<?php

namespace admin\settings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\settings\Requests\SettingCreateRequest;
use admin\settings\Requests\SettingUpdateRequest;
use admin\settings\Models\Setting;
use admin\admin_auth\Services\ImageService;

class SettingManagerController extends Controller
{
    protected $imageService;
    /**
     * SettingManagerController constructor.
     *
     * @param ImageService $imageService
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('admincan_permission:settings_manager_list')->only(['index']);
        $this->middleware('admincan_permission:settings_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:settings_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:settings_manager_view')->only(['show']);
        $this->middleware('admincan_permission:logo_favicon_manager_view')->only(['getlogos']);
        // $this->middleware('admincan_permission:settings_manager_delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            $settings = Setting::where('slug', '!=', 'industry')->where('setting_type', 'general')->filter($request->query('keyword'))
                ->sortable()
                ->latest()
                ->paginate(Setting::getPerPageLimit())
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
            return redirect()->route('admin.settings.index')->with('success', 'Setting created successfully.');
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

    public function getlogos(Request $request)
    {
        $settings = Setting::where('setting_type', 'theme_image')->get();
        return view('setting::admin.logo_favicon.view',compact('settings'));
    }

    public function storeLogos(Request $request)
    {
        try {
            $inputData = $request->input('setting');
            $fileData = $request->file('setting');
    
            foreach ($inputData as $index => $setting) {
                // Load existing setting to get current config_value
                $existing = Setting::find($setting['id']);
                $configValue = $existing->config_value ?? null;
    
                // If new file is uploaded, replace it
                if ($request->hasFile("setting.{$index}.config_value")) {
                    $file = $request->file("setting.{$index}.config_value");
                    $configValue = $this->imageService->upload($file, 'theme_images');
                }
    
                Setting::updateOrCreate(
                    ['id' => $setting['id']],
                    [
                        'config_value' => $configValue,
                    ]
                );
            }
    
            return redirect()->route('admin.settings.getlogos')->with(['success' => 'Settings saved successfully!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }
    
}
