<?php

namespace admin\settings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckModuleStatusCommand extends Command
{
    protected $signature = 'settings:status';
    protected $description = 'Check if Settings module files are being used';

    public function handle()
    {
        $this->info('Checking Settings Module Status...');
        
        // Check if module files exist
        $moduleFiles = [
            'Controller' => base_path('Modules/Settings/app/Http/Controllers/Admin/SettingManagerController.php'),
            'Model' => base_path('Modules/Settings/app/Models/Setting.php'),
            'Request (Create)' => base_path('Modules/Settings/app/Http/Requests/SettingCreateRequest.php'),
            'Request (Update)' => base_path('Modules/Settings/app/Http/Requests/SettingUpdateRequest.php'),
            'Routes' => base_path('Modules/Settings/routes/web.php'),
            'Views' => base_path('Modules/Settings/resources/views'),
            'Config' => base_path('Modules/Settings/config/settings.php'),
        ];

        $this->info("\n📁 Module Files Status:");
        foreach ($moduleFiles as $type => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$type}: EXISTS");
                
                // Check if it's a PHP file and show last modified time
                if (str_ends_with($path, '.php')) {
                    $lastModified = date('Y-m-d H:i:s', filemtime($path));
                    $this->line("   Last modified: {$lastModified}");
                }
            } else {
                $this->error("❌ {$type}: NOT FOUND");
            }
        }

        // Check namespace in controller
        $controllerPath = base_path('Modules/Settings/app/Http/Controllers/Admin/SettingManagerController.php');
        if (File::exists($controllerPath)) {
            $content = File::get($controllerPath);
            if (str_contains($content, 'namespace Modules\Settings\app\Http\Controllers\Admin;')) {
                $this->info("\n✅ Controller namespace: CORRECT");
            } else {
                $this->error("\n❌ Controller namespace: INCORRECT");
            }
        }

        // Check model namespace
        $modelPath = base_path('Modules/Settings/app/Models/Setting.php');
        if (File::exists($modelPath)) {
            $content = File::get($modelPath);
            if (str_contains($content, 'namespace Modules\Settings\app\Models;')) {
                $this->info("✅ Model namespace: CORRECT");
            } else {
                $this->error("❌ Model namespace: INCORRECT");
            }
        }

        // Check autoload in composer.json
        $composerPath = base_path('composer.json');
        $composer = json_decode(File::get($composerPath), true);
        
        if (isset($composer['autoload']['psr-4']['Modules\\Settings\\'])) {
            $this->info("\n✅ Composer autoload: CONFIGURED");
        } else {
            $this->error("\n❌ Composer autoload: NOT CONFIGURED");
        }

        // Check if package is in require section
        if (isset($composer['require']['admin/settings'])) {
            $this->info("✅ Package requirement: CONFIGURED");
        } else {
            $this->error("❌ Package requirement: NOT CONFIGURED");
        }

        $this->info("\n📋 Summary:");
        $this->info("Module files are " . (File::exists($controllerPath) ? "published" : "not published"));
        $this->info("Run 'php artisan settings:publish' to publish module files");
        $this->info("Run 'composer dump-autoload' after publishing");
    }
}
