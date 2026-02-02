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
        // نجيب كل اليوزرز (أعضاء التيم)
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
                'end_time' => '11:00',
            ],
            [
                'subject' => 'Client Meeting',
                'note' => 'Project requirements discussion',
                'date' => Carbon::today()->addDays(2),
                'start_time' => '12:00',
                'end_time' => '13:00',
            ],
            [
                'subject' => 'Sprint Planning',
                'note' => 'Plan tasks for next sprint',
                'date' => Carbon::today()->addDays(3),
                'start_time' => '09:30',
                'end_time' => '10:30',
            ],
            [
                'subject' => 'Team Sync',
                'note' => 'Weekly team sync-up',
                'date' => Carbon::today()->addDays(4),
                'start_time' => '11:00',
                'end_time' => '11:30',
            ],
            [
                'subject' => 'Demo Meeting',
                'note' => 'Project demo for stakeholders',
                'date' => Carbon::today()->addDays(5),
                'start_time' => '14:00',
                'end_time' => '15:00',
            ],
        ];

        foreach ($meetings as $meetingData) {
            // إنشاء الاجتماع
            $meeting = Meeting::create($meetingData);

            // اختيار أعضاء عشوائيين للاجتماع (من 2 إلى 4)
            $members = collect($users)
                ->shuffle()
                ->take(rand(2, 4))
                ->toArray();

            // ربط الأعضاء بالاجتماع
            $meeting->users()->attach($members);
        }
    }
}
