<?php

namespace App\Filament\Resources\CommentMeetingResource\Pages;

use App\Filament\Resources\CommentMeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommentMeeting extends EditRecord
{
    protected static string $resource = CommentMeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
