<?php

namespace admin\settings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishSettingsModuleCommand extends Command
{
    protected $signature = 'settings:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Settings module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Settings module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Settings');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'setting',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Settings module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/settings/src
        
        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/SettingManagerController.php' => base_path('Modules/Settings/app/Http/Controllers/Admin/SettingManagerController.php'),
            
            // Models
            $basePath . '/Models/Setting.php' => base_path('Modules/Settings/app/Models/Setting.php'),
            
            // Requests
            $basePath . '/Requests/SettingCreateRequest.php' => base_path('Modules/Settings/app/Http/Requests/SettingCreateRequest.php'),
            $basePath . '/Requests/SettingUpdateRequest.php' => base_path('Modules/Settings/app/Http/Requests/SettingUpdateRequest.php'),
            
            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/Settings/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\settings\\Controllers;' => 'namespace Modules\\Settings\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\settings\\Models;' => 'namespace Modules\\Settings\\app\\Models;',
            'namespace admin\\settings\\Requests;' => 'namespace Modules\\Settings\\app\\Http\\Requests;',
            
            // Use statements transformations
            'use admin\\settings\\Controllers\\' => 'use Modules\\Settings\\app\\Http\\Controllers\\Admin\\',
            'use admin\\settings\\Models\\' => 'use Modules\\Settings\\app\\Models\\',
            'use admin\\settings\\Requests\\' => 'use Modules\\Settings\\app\\Http\\Requests\\',
            
            // Class references in routes
            'admin\\settings\\Controllers\\SettingManagerController' => 'Modules\\Settings\\app\\Http\\Controllers\\Admin\\SettingManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = str_replace('use admin\\settings\\Models\\Setting;', 'use Modules\\Settings\\app\\Models\\Setting;', $content);
            $content = str_replace('use admin\\settings\\Requests\\SettingCreateRequest;', 'use Modules\\Settings\\app\\Http\\Requests\\SettingCreateRequest;', $content);
            $content = str_replace('use admin\\settings\\Requests\\SettingUpdateRequest;', 'use Modules\\Settings\\app\\Http\\Requests\\SettingUpdateRequest;', $content);
            $content = str_replace(
                'use admin\\admin_auth\\Services\\ImageService;',
                'use Modules\\AdminAuth\\app\\Services\\ImageService;',
                $content
            );
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Settings\\'])) {
            $composer['autoload']['psr-4']['Modules\\Settings\\'] = 'Modules/Settings/app/';
            
            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}