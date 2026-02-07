<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                Select::make('priority')
                    ->options(['high' => 'High', 'medium' => 'Medium', 'low' => 'Low'])
                    ->default('low')
                    ->required(),
                Select::make('status')
                    ->options(['todo' => 'Todo', 'progress' => 'Progress', 'review' => 'Review', 'completed' => 'Completed'])
                    ->default('todo')
                    ->required(),
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required(),
          
Select::make('user_id')
    ->label('Assigned User')
    ->relationship('user', 'full_name')
    ->searchable()
    ->preload()
    ->nullable(),

            ]);
    }
}
