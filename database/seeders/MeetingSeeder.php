<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meeting;
use App\Models\User;
use Carbon\Carbon;

class MeetingSeeder extends Seeder
{
    public function run(): void
    {
       
        $users = User::pluck('id')->toArray();

        if (empty($users)) {
            return;
        }

        $meetings = [
            [
                'subject' => 'Design Review',
                'note' => 'Discuss homepage UI and UX improvements',
                'date' => Carbon::today()->addDays(1),
                'start_time' => '10:00',
                'duration' => 60,
                'zoom_meeting_id' => '123456789',
                'join_url' => 'https://zoom.us/j/123456789',
               // 'end_time' => '11:00',
            ],
            [
                'subject' => 'Client Meeting',
                'note' => 'Project requirements discussion',
                'date' => Carbon::today()->addDays(2),
                'start_time' => '12:00',
                'duration' => 60,
                'zoom_meeting_id' => '987654321',
                'join_url' => 'https://zoom.us/j/987654321',
               // 'end_time' => '13:00',
            ],
            [
                'subject' => 'Sprint Planning',
                'note' => 'Plan tasks for next sprint',
                'date' => Carbon::today()->addDays(3),
                'start_time' => '09:30',
                'duration' => 60,
                'zoom_meeting_id' => '555555555',
                'join_url' => 'https://zoom.us/j/555555555',
              //  'end_time' => '10:30',
            ],
            [
                'subject' => 'Team Sync',
                'note' => 'Weekly team sync-up',
                'date' => Carbon::today()->addDays(4),
                'start_time' => '11:00',
                'duration' => 30,
                'zoom_meeting_id' => '222222222',
                'join_url' => 'https://zoom.us/j/222222222',
              //  'end_time' => '11:30',
            ],
            [
                'subject' => 'Demo Meeting',
                'note' => 'Project demo for stakeholders',
                'date' => Carbon::today()->addDays(5),
                'start_time' => '14:00',
                'duration' => 60,
                'zoom_meeting_id' => '333333333',
                'join_url' => 'https://zoom.us/j/333333333',
               // 'end_time' => '15:00',
            ],
        ];

        foreach ($meetings as $meetingData) {
            $meeting = Meeting::create($meetingData);

            $members = collect($users)
                ->shuffle()
                ->take(rand(2, 4))
                ->toArray();

            $meeting->users()->attach($members);
        }
    }
}
