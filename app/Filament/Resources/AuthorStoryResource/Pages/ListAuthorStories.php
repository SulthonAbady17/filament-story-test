<?php

namespace App\Filament\Resources\AuthorStoryResource\Pages;

use App\Filament\Resources\AuthorStoryResource;
use App\Models\Story;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAuthorStories extends ListRecords
{
    protected static string $resource = AuthorStoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(Story::query()->where('user_id', auth()->id())->count()),
            'published' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_published', true))
                ->badge(Story::query()->where('user_id', auth()->id())->where('is_published', true)->count()),
            'unpublished' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_published', false))
                ->badge(Story::query()->where('user_id', auth()->id())->where('is_published', false)->count()),
        ];
    }
}
