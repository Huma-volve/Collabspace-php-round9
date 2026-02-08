<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                // TextColumn::make('description'),
                TextColumn::make('start_date'),
                TextColumn::make('end_date'),
                 TextColumn::make('type'),
                TextColumn::make('priority'),
                TextColumn::make('status'),

            ])
            ->filters([
                SelectFilter::make('priority')
                ->label('Select Priority From Options')
                ->options([
                    'low'=>'Low',
                    'medium'=>'Medium',
                    'high'=>'High'
                ])->preload(),
                SelectFilter::make('status')
                ->label('Select Status From Options')
                ->options([
                    '0'=>'Block',
                    '1'=>'Active',

                ])->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
