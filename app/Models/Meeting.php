<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meeting extends Model
{
   use HasFactory;
protected $fillable = [
        'subject',
        'note',
        'date',    
        'start_time',  
     //   'end_time',    
        'duration',    
        'zoom_meeting_id',
        'join_url',        
       // 'start_url',       
    ];
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
     protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];
    public function comments()
    {
        return $this->hasMany(CommentMeeting::class);
    }
    public function calculateEndTime()
{
    return \Carbon\Carbon::parse($this->start_time)
        ->addMinutes($this->duration)
        ->format('H:i');
}

}
