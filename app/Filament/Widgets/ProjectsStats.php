<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectsStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Projects', Project::count())
                ->icon('heroicon-o-briefcase')
                ->color('success'),
                Stat::make('Active Projects', Project::where('status', 1)->count())
                ->icon('heroicon-o-briefcase')
                ->color('primary'),
                Stat::make('Inactive Projects', Project::where('status', 0)->count())
                ->icon('heroicon-o-briefcase')
                ->color('gray'),

        ];

    }
}

