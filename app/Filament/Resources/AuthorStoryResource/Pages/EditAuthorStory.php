<?php

namespace App\Filament\Resources\AuthorStoryResource\Pages;

use App\Filament\Resources\AuthorStoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuthorStory extends EditRecord
{
    protected static string $resource = AuthorStoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
