<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentMeetingResource\Pages;
use App\Filament\Resources\CommentMeetingResource\RelationManagers;
use App\Models\CommentMeeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
class CommentMeetingResource extends Resource
{
    protected static ?string $model = CommentMeeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('meeting_id')
                ->label('Meeting')
                ->relationship('meeting', 'subject') 
                ->searchable()
                ->preload()
                ->required(),

            Select::make('user_id')
                ->label('User')
                ->relationship('user', 'full_name') 
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Textarea::make('comment')
                ->required()
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->toggleable()
                ->sortable(),
             Tables\Columns\TextColumn::make('meeting.subject')
                    ->toggleable()
                ->label('Meeting')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('user.full_name')
                    ->toggleable()
                ->label('User')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('comment')
                    ->toggleable()
                ->limit(50)
                ->wrap(),
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
            'index' => Pages\ListCommentMeetings::route('/'),
            'create' => Pages\CreateCommentMeeting::route('/create'),
            'edit' => Pages\EditCommentMeeting::route('/{record}/edit'),
        ];
    }
}
