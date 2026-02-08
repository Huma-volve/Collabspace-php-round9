<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               TextInput::make('name'),
                TextInput::make('description'),
                TextInput::make('start_date'),

                TextInput::make('end_date'),
                TextInput::make('priority'),
                TextInput::make('status'),
                Select::make('project_id')
                ->relationship('project','name')

                ->required(),
                Select::make('user_id') //one to many
                ->relationship('user','full_name')

                ->required()
            ]);
    }
}
