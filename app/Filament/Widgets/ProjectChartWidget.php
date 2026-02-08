<?php

namespace App\Filament\Widgets;
use App\Models\Project;
use Filament\Widgets\ChartWidget;

class ProjectChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart';

   protected function getData(): array
{
    $projectsPerMonth = Project::query()
        ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    $data = [];
    for ($month = 1; $month <= 12; $month++) {
        $data[] = $projectsPerMonth[$month] ?? 0;
    }

    return [
        'datasets' => [
            [
                'label' => 'Projects Overview',
                'data' => $data,
            ],
        ],
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    ];
}

    protected function getType(): string
    {
        return 'line';
    }
}
