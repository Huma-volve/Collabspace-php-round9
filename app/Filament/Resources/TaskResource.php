<?php

namespace App\Filament\Resources;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TaskResource\RelationManagers\FilesRelationManager;
use App\Filament\Resources\TaskResource\RelationManagers\UsersRelationManager;
use Filament\Tables\Columns\TextColumn;
class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                  Forms\Components\Placeholder::make('hint')
                     ->content('Save the task first to upload files.')
                     ->columnSpanFull()
                     ->visible(fn (? \App\Models\Task $record) => $record === null),
                Forms\Components\TextInput::make('name')
                    ->required()
                 ->nullable()
                    ->maxLength(191),
                Forms\Components\Textarea::make('description')
                    ->required()
                 ->nullable()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('start_date')
                    ->required()
                 ->nullable(),
                Forms\Components\DatePicker::make('end_date')
                    ->required(),
                Forms\Components\Select::make('priority')
                    ->label('Priority')
                    ->options([
                        'low'    => 'Low',
                        'medium' => 'Medium',
                        'high'   => 'High',
                    ])
                ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'todo'    => 'Todo',
                        'progress' => 'In Progress',
                        'review'   => 'Review',
                        'completed'   => 'Completed',
                    ])
                ->required(),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Assigned User')
                    ->relationship('user', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               Tables\Columns\TextColumn::make('id')
                ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                ->toggleable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user.full_name')
                ->label('Assigned User')
                    ->sortable()
                    ->toggleable(),
                    TextColumn::make('files_count')
                        ->counts('files')
                        ->label('Files'),
                        TextColumn::make('comments_count')
                        ->counts('comments')
                        ->label('Comments'),
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
            FilesRelationManager::class,
            // UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
