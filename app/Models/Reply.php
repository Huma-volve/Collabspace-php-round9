<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reply extends Model
{
protected $guarded = [];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
}
