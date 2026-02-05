<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Tasks',Task::where('status','review')->count())
            ->description('Count of pending tasks'),
             Stat::make('Progress Tasks',Task::where('status','progress')->count())
            ->description('Count of progress tasks'),
             Stat::make('Completed Tasks',Task::where('status','completed')->count())
            ->description('Count of completed tasks'),
             Stat::make('Todo Tasks',Task::where('status','todo')->count())
            ->description('Count of todo tasks')

        ];
    }
}
