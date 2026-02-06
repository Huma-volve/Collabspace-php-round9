<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileResource\Pages;
use App\Filament\Resources\FileResource\RelationManagers;
use App\Models\File;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->sortable(),
                Tables\Columns\IconColumn::make('url')
                    ->label('File')
                    ->icon(fn ($record) => match (pathinfo($record->url, PATHINFO_EXTENSION)) {
                        'pdf'  => 'heroicon-o-document-text',
                        'jpg', 'png', 'jpeg' => 'heroicon-o-photo',
                        'zip'  => 'heroicon-o-archive-box',
                        default => 'heroicon-o-paper-clip',
                    })
                    ->url(fn ($record) => asset('storage/' . $record->url))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('fileable_type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->badge(),

                Tables\Columns\TextColumn::make('uploaded_by')
                    ->label('Uploader')
                    ->formatStateUsing(fn ($record) => $record->uploader?->full_name)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])->searchDebounce('750ms')
            ->filters([
                SelectFilter::make('fileable_type')
    ->options([
        \App\Models\Task::class    => 'Task',
        \App\Models\Comment::class => 'Comment',
        \App\Models\Meeting::class => 'Meeting',
    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
    ->icon('heroicon-o-arrow-down-tray')
    ->url(fn ($record) => asset('storage/' . $record->url))
    ->openUrlInNewTab(),

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
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
{
    return false;
}

}
