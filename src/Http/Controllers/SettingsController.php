<?php

namespace Yassir3wad\Settings\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Contracts\Resolvable;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use Yassir3wad\Settings\SettingsTool;

class SettingsController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = config('settings.model');
    }

    public function index(Request $request)
    {
        $fields = SettingsTool::getFields();
        $settings = $this->model::whereActive(true)->get();

        $fields->whereInstanceOf(Resolvable::class)
            ->filter(function (Field $field) use ($settings) {
                return $settings->firstWhere("name", $field->attribute);
            })
            ->each(function (Field &$field) use ($settings) {
                $setting = $settings->firstWhere("name", $field->attribute);
                $field->resolve([$field->attribute => $setting->value]);
                $field->withMeta(['section' => $setting->section]);
            });

        return $fields;
    }

    public function update(NovaRequest $request)
    {
        $fields = SettingsTool::getFields();

        $this->validateFields($request, $fields);
        $fields->whereInstanceOf(Resolvable::class)->each(function (Field $field) use ($request) {
            $tempResource = new $this->model();
            $field->fill($request, $tempResource);
            $this->model::updateOrCreate(['name' => $field->attribute], ['value' => $tempResource->{$field->attribute}]);
        });

        return response()->json([]);
    }

    private function validateFields(NovaRequest $request, Collection $fields)
    {
        $rules = $fields->mapWithKeys(function (Field $field) use ($request) {
            return $field->getCreationRules($request);
        })->all();

        Validator::make($request->all(), $rules)->validate();
    }
}
