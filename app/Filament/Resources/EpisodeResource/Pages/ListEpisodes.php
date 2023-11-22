<?php

namespace App\Filament\Resources\EpisodeResource\Pages;

use App\Filament\Resources\EpisodeResource;
use App\Models\Episode;
use App\Models\Story;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEpisodes extends ListRecords
{
    protected static string $resource = EpisodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs() : array {
        return [
            'all' => Tab::make()
                ->badge(Episode::query()->count()),
            'published' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_published', true))
                // ->icon('heroicon-o-check-circle')
                ->badge(Episode::query()->where('is_published', true)->count()),
            'unpublished' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_published', false))
                ->badge(Episode::query()->where('is_published', false)->count()),
        ];
    }
}
