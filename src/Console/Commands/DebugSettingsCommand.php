<?php

namespace admin\settings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DebugSettingsCommand extends Command
{
    protected $signature = 'settings:debug';
    protected $description = 'Debug Settings module routing and view resolution';

    public function handle()
    {
        $this->info('🔍 Debugging Settings Module...');

        // Check route file loading
        $this->info("\n📍 Route Files:");
        $moduleRoutes = base_path('Modules/Settings/routes/web.php');
        if (File::exists($moduleRoutes)) {
            $this->info("✅ Module routes found: {$moduleRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($moduleRoutes)));
        } else {
            $this->error("❌ Module routes not found");
        }

        $packageRoutes = base_path('packages/admin/settings/src/routes/web.php');
        if (File::exists($packageRoutes)) {
            $this->info("✅ Package routes found: {$packageRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($packageRoutes)));
        } else {
            $this->error("❌ Package routes not found");
        }
        
        // Check view loading priority
        $this->info("\n👀 View Loading Priority:");
        $viewPaths = [
            'Module views' => base_path('Modules/Settings/resources/views'),
            'Published views' => resource_path('views/admin/setting'),
            'Package views' => base_path('packages/admin/settings/resources/views'),
        ];
        
        foreach ($viewPaths as $name => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$name}: {$path}");
            } else {
                $this->warn("⚠️  {$name}: NOT FOUND - {$path}");
            }
        }
        
        // Check controller resolution
        $this->info("\n🎯 Controller Resolution:");
        $controllerClass = 'Modules\\Settings\\app\\Http\\Controllers\\Admin\\SettingManagerController';
        if (class_exists($controllerClass)) {
            $this->info("✅ Controller class exists: {$controllerClass}");
        } else {
            $this->error("❌ Controller class not found: {$controllerClass}");
        }

        // Check model resolution
        $this->info("\n🏗️  Model Resolution:");
        $modelClass = 'Modules\\Settings\\app\\Models\\Setting';
        if (class_exists($modelClass)) {
            $this->info("✅ Model class exists: {$modelClass}");
        } else {
            $this->error("❌ Model class not found: {$modelClass}");
        }

        $this->info("\n📝 Recommendations:");
        $this->info("- Module files take priority over package files");
        $this->info("- If module view doesn't exist, it will fallback to package view");
    }
}
