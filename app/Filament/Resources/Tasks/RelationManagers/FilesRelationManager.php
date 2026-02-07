<?php

namespace App\Filament\Resources\Tasks\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;

use Filament\Tables\Columns\ViewColumn;
class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
ViewColumn::make('preview')
    ->label('Preview')
    ->view('filament.tables.columns.file-preview'),

                TextColumn::make('uploader.full_name')
                    ->label('Uploaded By'),

                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Upload File')
                    ->schema([
                        FileUpload::make('url')
                            ->disk('public')
                            ->directory('uploads')
                            
                            ->required(),
                    ])
                    ->mutateDataUsing(function (array $data) {
                        $data['uploaded_by'] = Auth::id();
                        return $data;
                    }),
            ])
            ->recordActions([
                DeleteAction::make(),
            ]);
    }
}
