<?php

namespace Yassir3wad\Settings\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Nova\Resource;

class Setting extends Resource
{
    public static $displayInNavigation = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Yassir3wad\Settings\Models\Setting::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'section', 'key'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $result = [];
        foreach (Setting::all() as $setting) {
            $result[] = (\config('settings.' . $setting->type))::make(str_replace("_", " ", Str::title($setting->key)), $setting->key)->withMeta(['value' => $setting->value, 'section' => $setting->section]);
        }
        return $result;
    }
}
