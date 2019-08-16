<?php

namespace Yassir3wad\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['section', 'name', 'value', 'active'];

    protected $attributes = [
        'active' => true
    ];

    protected $casts = ['active' => 'boolean'];
}
