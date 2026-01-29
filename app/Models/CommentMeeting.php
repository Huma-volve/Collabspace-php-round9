<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentMeeting extends Model
{
    protected $fillable = [
        'meeting_id',
        'user_id',
        'comment',
    ];
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMeetingComments($query, $meetingId)
    {
        return $query->where('meeting_id', $meetingId);
    }

}
