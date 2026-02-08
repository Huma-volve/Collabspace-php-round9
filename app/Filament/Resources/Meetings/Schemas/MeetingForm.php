<?php

namespace App\Filament\Resources\Meetings\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class MeetingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subject'),
                TextInput::make('note'),
                TextInput::make('date'),
                TextInput::make('start_time'),
                TextInput::make('end_time'),
                Select::make('users') //many to many
                ->multiple()
                ->relationship('users','full_name')
                ->preload()

            ]);
    }
}
