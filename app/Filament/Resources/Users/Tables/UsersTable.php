<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                     TextColumn::make('full_name'),
                TextColumn::make('email'),
                TextColumn::make('phone'),

                ImageColumn::make('image'),

                TextColumn::make('job_title'),
                TextColumn::make('role'),
                TextColumn::make('experience')
            ])
            ->filters([
                SelectFilter::make('role')
                ->label('Select Role From Options')
                ->options([
                    'admin'=>'Admin',
                    'employee'=>'Employee'
                ]),

                SelectFilter::make('Experience')
                ->label('Select Experience From Options')
                ->options([
                    'junior'=>'Junior',
                    'mid'=>'Mid',
                    'senior'=>'Senior'

                ])
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
