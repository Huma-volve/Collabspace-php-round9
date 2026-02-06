<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(191),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(191),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(191),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('users')
                    ->visibility('public'),
                Forms\Components\TextInput::make('job_title')
                    ->maxLength(191),
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin'    => 'Admin',
                        'employee' => 'Employee',
                    ])
                ->required(),
                Select::make('status')
                    ->label('Account Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
                    ->default(1)
                    ->required(),
                Select::make('availability')
                    ->label('Availability')
                    ->options([
                        1 => 'Available',
                        0 => 'Not Available',
                    ])
                    ->default(1)
                    ->required(),
                Forms\Components\Textarea::make('about')
                    ->columnSpanFull(),
                Select::make('experience')
                    ->label('Experience Level')
                    ->options([
                        'junior' => 'Junior',
                        'mid'    => 'Mid',
                        'senior' => 'Senior',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('experience_year')
                    ->numeric(),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->toggleable()
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('email')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->toggleable()
                    ->disk('public')
                    ->visibility('public')
                    ->height(60)
                    ->square(), 
                Tables\Columns\TextColumn::make('job_title')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('status')
                    ->toggleable()
                    ->boolean(),
                Tables\Columns\IconColumn::make('availability')
                    ->toggleable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('experience')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('experience_year')
                    ->toggleable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->searchDebounce('750ms')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
