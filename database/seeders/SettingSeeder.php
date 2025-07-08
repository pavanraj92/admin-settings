<?php

namespace Packages\Admin\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingSeeder extends Seeder
{
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
            ],
            [
                'slug' => 'admin_date_format',
                'title' => 'Admin Date Format',
                'config_value' => 'd F, Y',
            ],
            [
                'slug' => 'admin_date_time_format',
                'title' => 'Admin Date Time Format',
                'config_value' => 'd F, Y H:i A',
            ]
        ];
    
        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['slug' => $setting['slug']], // unique key
                [
                    'title' => $setting['title'],
                    'config_value' => $setting['config_value'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
        $this->command->info('Settings seeded successfully.');
    }
}
