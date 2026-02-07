<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                FileUpload::make('image')
                    ->image()
                    ->default('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR99-ZMZeEtYlFVdT-HN3Hz0f_i64Zf76D67g&s'),
                TextInput::make('job_title')
                    ->default(null),
                Select::make('role')
                    ->options(['admin' => 'Admin', 'employee' => 'Employee'])
                    ->default('employee')
                    ->required(),
                Toggle::make('status')
                    ->required(),
                Toggle::make('availability')
                    ->required(),
                Textarea::make('about')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('experience')
                    ->options(['junior' => 'Junior', 'mid' => 'Mid', 'senior' => 'Senior'])
                    ->required(),
                TextInput::make('experience_year')
                    ->numeric()
                    ->default(null),
                Select::make('team_id')
                    ->relationship('team', 'name')
                    ->default(null),
            ]);
    }
}
