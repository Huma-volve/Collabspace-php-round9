<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Project extends Model
{
use HasFactory;
protected $guarded = [];
    public function files(): MorphMany {
        return $this->morphMany(File::class, 'fileable');
    }

    public function tasks(): HasMany {
        return $this->hasMany(Task::class);
    }

    public function teams(): BelongsToMany {
        return $this->belongsToMany(Team::class);
    }

    public function getStatusTextAttribute(){
        return $this->status==0?'active':'done';
    }
    public function getuppercase(){
        return strtoupper($this->name);
    }
}
