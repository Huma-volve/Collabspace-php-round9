<?php

namespace App\Filament\Resources\Tasks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('description'),
                TextColumn::make('start_date'),

                TextColumn::make('end_date'),
                TextColumn::make('priority'),
                TextColumn::make('status'),
                TextColumn::make('project.name')
                ->label('Projectname')
                ->sortable(),
                TextColumn::make('user.name')
                ->label('Username')
                ->sortable()

            ])
            ->filters([
                SelectFilter::make('status')
                ->label('Select Status From Options')
                ->options([
                    'todo'=>'Todo',
                    'progress'=>'Inprogress',
                    'review'=>'Review',
                    'completed'=>'Completed'
                ])
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
