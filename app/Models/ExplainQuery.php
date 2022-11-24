<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExplainQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'query', 'bindings', 'explain', 'previous_query_id'
    ];

    protected $casts = [
        'bindings' => 'json',
        'explain' => 'json'
    ];

    public function previousQuery(): BelongsTo
    {
        return $this->belongsTo(ExplainQuery::class, 'previous_query_id');
    }

    public function nextQuery(): HasOne
    {
        return $this->hasOne(ExplainQuery::class, 'previous_query_id');
    }

}
