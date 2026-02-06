<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextInput::make('full_name'),
                TextInput::make('email'),
                TextInput::make('password'),
                TextInput::make('phone'),

                FileUpload::make('image')
                ->disk('public')
                ->directory('images')
                ->nullable()
                ->columnSpanFull(),
                TextInput::make('job_title'),
                TextInput::make('role'),
                TextInput::make('experience')->columnSpanFull()
            ]);
    }
}
