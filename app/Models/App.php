<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;
    protected $casts = [
        'binded_ips' => 'array'
    ];
    // as The json datatype can not have default values in MySQL. Use the $attributes instead.
    protected $attributes = [
        'binded_ips' => [],
    ];
}
