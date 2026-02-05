<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use name;

class RecentTasks extends TableWidget
{
    protected static ?int $sort = 4;
    protected function getTableQuery(): Builder|Relation|null
    {
        return Task::query()->latest()->limit(4);
    }
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultSort('start_date','desc')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('priority')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
