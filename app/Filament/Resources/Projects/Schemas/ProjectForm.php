<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use function Pest\Laravel\options;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextInput::make('name')->required(),
                TextInput::make('description'),
                TextInput::make('start_date'),
                TextInput::make('end_date')->after('start_date'),
                 TextInput::make('type'),
                Select::make('priority')
                ->options([
                    'low'=>'Low',
                    'medium'=>'Medium',
                    'high'=>'High'
                ]),
             Select::make('status')
            -> options([
                0=>'block',
                1=>'active'
             ]),
            ]);
    }
}
