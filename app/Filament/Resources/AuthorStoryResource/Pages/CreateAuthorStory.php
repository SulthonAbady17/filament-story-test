<?php

namespace App\Filament\Resources\AuthorStoryResource\Pages;

use App\Filament\Resources\AuthorStoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthorStory extends CreateRecord
{
    protected static string $resource = AuthorStoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
