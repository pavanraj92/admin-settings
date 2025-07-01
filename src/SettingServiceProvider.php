<?php

namespace admin\settings;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SettingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package
        // $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->registerAdminRoutes();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'setting');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/setting.php', 'setting.constants');
        

        $this->publishes([  
            __DIR__.'/../resources/views' => resource_path('views/admin/setting'),
            __DIR__ . '/../config/setting.php' => config_path('constants/admin/setting.php'),
            __DIR__ . '/../src/Controllers' => app_path('Http/Controllers/Admin/SettingManager'),
            __DIR__ . '/../src/Models' => app_path('Models/Admin/Setting'),
            __DIR__ . '/routes/web.php' => base_path('routes/admin/admin_setting.php'),
        ], 'setting');


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
                $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            });
    }

    public function register()
    {
        // You can bind classes or configs here
    }
}
