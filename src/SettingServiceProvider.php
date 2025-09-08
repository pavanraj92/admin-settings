<?php

namespace admin\settings;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SettingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/Settings/resources/views'), // Published module views first
            resource_path('views/admin/setting'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'setting');
        
        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Settings/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Settings/resources/views'), 'settings-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/Settings/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Settings/database/migrations'));
        }
        $this->mergeConfigFrom(__DIR__ . '/../config/setting.php', 'setting.constants');
        // Also merge config from published module if it exists
        if (file_exists(base_path('Modules/Settings/config/settings.php'))) {
            $this->mergeConfigFrom(base_path('Modules/Settings/config/settings.php'), 'setting.constants');
        }
        
        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../config/' => base_path('Modules/Settings/config/'),
            __DIR__ . '/../database/migrations' => base_path('Modules/Settings/database/migrations'),
            __DIR__ . '/../database/seeders' => base_path('Modules/Settings/database/seeders'),
            __DIR__ . '/../resources/views' => base_path('Modules/Settings/resources/views/'),
        ], 'setting');
       
        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $slug = DB::table('admins')->latest()->value('website_slug') ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                // Load routes from published module first, then fallback to package
                if (file_exists(base_path('Modules/Settings/routes/web.php'))) {
                    $this->loadRoutesFrom(base_path('Modules/Settings/routes/web.php'));
                } else {
                    $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
                }
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\settings\Console\Commands\PublishSettingsModuleCommand::class,
                \admin\settings\Console\Commands\CheckModuleStatusCommand::class,
                \admin\settings\Console\Commands\DebugSettingsCommand::class,
            ]);
        }
    }
}
