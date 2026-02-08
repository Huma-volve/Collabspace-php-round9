<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Auth;
class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
              FileUpload::make('url')
    ->disk('public')
    ->directory('projects')
    ->visibility('public')
    ->preserveFilenames()
    ->openable()
->downloadable()
    ->required(),
]);
    }

public function table(Table $table): Table
{
    return $table
        ->recordTitleAttribute('url')
        ->columns([
            Tables\Columns\TextColumn::make('url')
                ->label('File')
                ->formatStateUsing(fn ($state) => basename($state))
                ->searchable(),

            Tables\Columns\TextColumn::make('uploader.full_name')
                ->label('Uploaded By')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['uploaded_by'] = auth()->id();
                    return $data;
                }),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}

}
