<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                Select::make('leader_id')
                ->relationship('leader','full_name')
            ]);
    }
}
