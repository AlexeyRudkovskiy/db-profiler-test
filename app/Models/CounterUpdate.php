<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterUpdate extends Model
{
    use HasFactory;

    protected $fillable = [ 'action', 'user_id', 'counter_id' ];

}
