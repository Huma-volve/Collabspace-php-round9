<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('full_name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                ImageEntry::make('image'),
                TextEntry::make('job_title')
                    ->placeholder('-'),
                TextEntry::make('role')
                    ->badge(),
                IconEntry::make('status')
                    ->boolean(),
                IconEntry::make('availability')
                    ->boolean(),
                TextEntry::make('about')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('experience')
                    ->badge(),
                TextEntry::make('experience_year')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('team.name')
                    ->label('Team')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
