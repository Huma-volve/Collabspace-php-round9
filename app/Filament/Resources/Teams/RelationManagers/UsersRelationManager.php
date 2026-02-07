<?php

namespace App\Filament\Resources\Teams\RelationManagers;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('user_id')
                ->label('Select User')
                ->options(
                    User::whereNull('team_id')->pluck('full_name', 'id')
                )
                ->searchable()
                ->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')->circular(),
                TextColumn::make('full_name'),
                TextColumn::make('email'),
            ])
            ->headerActions([
                Action::make('addMember')
                    ->label('Add Member')
                    ->icon('heroicon-o-user-plus')
                    ->form([
                        Select::make('user_id')
                            ->label('User')
                            ->options(
                                User::whereNull('team_id')->pluck('full_name', 'id')
                            )
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        User::where('id', $data['user_id'])
                            ->update([
                                'team_id' => $this->ownerRecord->id,
                            ]);
                    }),
            ]);
    }
}
