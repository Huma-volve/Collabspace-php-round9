<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextInput::make('name'),
                TextInput::make('description'),
                TextInput::make('start_date'),
                TextInput::make('end_date'),
                 TextInput::make('type'),
                TextInput::make('priority'),
                TextInput::make('status'),
            ]);
    }
}
