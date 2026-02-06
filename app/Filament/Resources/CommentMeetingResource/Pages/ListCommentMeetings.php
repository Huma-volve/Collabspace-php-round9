<?php

namespace App\Filament\Resources\CommentMeetingResource\Pages;

use App\Filament\Resources\CommentMeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommentMeetings extends ListRecords
{
    protected static string $resource = CommentMeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
