<?php

namespace App\Filament\Resources\Meetings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MeetingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject'),
                TextColumn::make('note'),
                TextColumn::make('date')->date(),
                TextColumn::make('start_url'),
                TextColumn::make('join_url'),
                TextColumn::make('start_time')->dateTime('H:m:s'),
                TextColumn::make('end_time')->dateTime('H:m:s'),
                TextColumn::make('users.full_name')
                ->label('names')
            ])
            ->filters([
                //
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
