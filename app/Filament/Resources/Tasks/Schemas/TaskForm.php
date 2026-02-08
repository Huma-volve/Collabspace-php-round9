<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Models\Task;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;

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
                ->relationship('user','full_name'),


             
            ]);
    }
}
