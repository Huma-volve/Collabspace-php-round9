<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TasksStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('All Tasks', Task::count())
                ->icon('heroicon-o-clipboard-document'),

            Stat::make('Pending', Task::where('status', 'pending')->count())
                ->color('warning'),

            Stat::make('In Progress', Task::where('status', 'in_progress')->count())
                ->color('info'),

            Stat::make('Done', Task::where('status', 'done')->count())
                ->color('success'),
        
        ];
    }
}

