<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Task;
use Filament\Widgets\ChartWidget;

class ProjectsVsTasks extends ChartWidget
{
    protected  ?string $heading = 'Projects vs Tasks';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Count',
                    'data' => [
                        Project::count(),
                        Task::count(),
                    ],
                ],
            ],
            'labels' => ['Projects', 'Tasks'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

