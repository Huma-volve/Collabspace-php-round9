<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;

class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';
protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Teams';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->date(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Add Team')
                    ->preloadRecordSelect(),
            ])
            ->actions([
                DetachAction::make()
                    ->label('Remove'),
            ]);
    }
}
