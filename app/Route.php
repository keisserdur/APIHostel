<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'distance', 'id', 'duration',
    ];
}
