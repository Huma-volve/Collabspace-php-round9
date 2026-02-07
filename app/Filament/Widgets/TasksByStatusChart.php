<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;

class TasksByStatusChart extends ChartWidget
{
    protected ?string $heading = 'Tasks by Status';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'data' => [
                        Task::where('status', 'todo')->count(),
                        Task::where('status', 'progress')->count(),
                        Task::where('status', 'review')->count(),
                        Task::where('status', 'completed')->count(),
                    ],
                    'backgroundColor' => [
                        '#ef4444', // red
                        '#3b82f6', // blue
                        '#facc15', // yellow
                        '#22c55e', // green
                    ],
                ],
            ],
            'labels' => [
                'Pending',
                'In Progress',
                'Review',
                'Completed',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
