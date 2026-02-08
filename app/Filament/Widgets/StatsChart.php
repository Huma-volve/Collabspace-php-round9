<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

// use function Symfony\Component\Clock\now;

class StatsChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan ='full';

    protected ?string $heading = 'Project overview';

    protected function getData(): array
    {

$data=Trend::model(Project::class)
->between(
   start: now()->startOfYear(),
   end: now()->endOfYear(),
)
->perMonth()
->count();
        return [
            'datasets'=>[
                [
                    'label'=>'Project overview',
                    'data'=>$data->map(fn(TrendValue $item) =>$item->aggregate )->toArray(),
                    'fill' => 'start',
        'tension' => 0.4,
        'backgroundColor' => 'rgba(54, 162, 235, .1)',
        'borderColor' => 'rgb(54, 162, 235)',

                ]
            ],
            'labels'=>$data->map(fn(TrendValue $item) =>$item->date)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
