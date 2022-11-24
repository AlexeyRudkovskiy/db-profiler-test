<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'queries',
        'category',
        'query'
    ];

    protected $casts = [
        'queries' => 'json'
    ];

}
