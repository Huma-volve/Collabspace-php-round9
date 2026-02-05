<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Tables;
use App\Models\Project;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class ActiveProjects extends BaseWidget
{
    // الترتيب والمساحة عشان يجي جنب الرسم البياني
    protected int | string | array $columnSpan = 1;
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Active Projects';

    public function table(Table $table): Table
    {
        return $table
            ->query(Project::query()->where('status', 1)->limit(3))
            ->columns([
                TextColumn::make('name')
                    ->label('Task')
                    ->icon('heroicon-m-briefcase')
                    ->description(fn (Project $record): string => "Tasks: {$record->tasks->count()}"),

            ])
            ->paginated(false);
    }
}
