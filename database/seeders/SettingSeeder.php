<?php

namespace Admin\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use admin\admin_auth\Services\ImageService;
use admin\settings\Models\Setting;

class SettingSeeder extends Seeder
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'slug' => 'admin_page_limit',
                'title' => 'Admin Page Limit',
                'config_value' => '10',
                'setting_type' => 'general'
            ],
            [
                'slug' => 'admin_date_format',
                'title' => 'Admin Date Format',
                'config_value' => 'd F, Y',
                'setting_type' => 'general'
            ],
            [
                'slug' => 'admin_date_time_format',
                'title' => 'Admin Date Time Format',
                'config_value' => 'd F, Y H:i A',
                'setting_type' => 'general'
            ],
            [
                'slug' => 'main_logo',
                'title' => 'Main Logo',
                'config_value' => 'images/dots-logo.svg',
                'setting_type' => 'theme_image'
            ],
            [
                'slug' => 'main_favicon',
                'title' => 'Main Favicon',
                'config_value' => 'images/favicon.ico',
                'setting_type' => 'theme_image'
            ],
            [
                'slug' => 'default_currency',
                'title' => 'Default currency',
                'config_value' => 'USD',
                'setting_type' => 'general'
            ],
            [
                'slug' => 'currency_sign',
                'title' => 'Currency Sign',
                'config_value' => '$',
                'setting_type' => 'general'
            ]
        ];
    
        foreach ($settings as $setting) {
            if ($setting['setting_type'] === 'theme_image') {
                $sourcePath = public_path($setting['config_value']); 

                if (File::exists($sourcePath)) {
                    // Create UploadedFile instance manually
                    $uploadedFile = new UploadedFile(
                        $sourcePath,
                        basename($sourcePath),
                        File::mimeType($sourcePath),
                        null,
                        true // Mark as test (skip move validation)
                    );

                    // Upload using your service
                    $storedPath = $this->imageService->upload($uploadedFile, 'theme_images');
                    $setting['config_value'] = $storedPath;
                } else {
                    $this->command->warn("Image file not found: $sourcePath");
                }
            }
            Setting::updateOrCreate(
                ['slug' => $setting['slug']], // unique key
                [
                    'title' => $setting['title'],
                    'config_value' => $setting['config_value'],
                    'setting_type' => $setting['setting_type'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
        $this->command->info('Settings seeded successfully.');
    }
}
