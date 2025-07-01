<?php

namespace admin\settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'config_value',
    ];


    public function scopeFilter($query, $keyword) {
        if (!empty($keyword)) {
            $query->where(function($query) use ($keyword) {
                $query->where('title', 'LIKE', '%' . $keyword . '%');
            });
        }
        return $query;
    }
    
    protected static function boot()
    {
        parent::boot();

        // Slug generation
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });

        static::updating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }

            $model->yamlParse();
        });

        // Sync YAML on save/update/delete
        static::saved(function ($model) {
            $model->yamlParse();
        });

        static::deleted(function ($model) {
            $model->yamlParse();
        });
    }

    protected function yamlParse()
    {
        $settings = DB::table('settings')->pluck('config_value','slug')->toArray();

        $listYaml = Yaml::dump($settings, 4, 60);
        Storage::disk('configuration')->put('settings.yml', $listYaml);
    }
}

