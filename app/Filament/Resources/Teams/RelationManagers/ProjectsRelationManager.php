<?php

namespace App\Filament\Resources\Teams\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    protected static ?string $title = 'Projects';
protected static ?string $recordTitleAttribute = 'name';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->date(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Attach Project')
                    ->preloadRecordSelect(),
            ])
            ->actions([
                DetachAction::make(),
            ]);
    }
}
