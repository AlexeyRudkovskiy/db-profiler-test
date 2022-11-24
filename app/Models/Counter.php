<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = [ 'value', 'category_id' ];
    protected $with = [ 'counterUpdates' ];

    public function counterUpdates(): HasMany
    {
        return $this->hasMany(CounterUpdate::class);
    }

}
