<?php

namespace App\Filament\Resources\StoryResource\Pages;

use App\Filament\Resources\EpisodeResource;
use App\Filament\Resources\StoryResource;
use App\Models\Episode;
use Filament\Actions;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewStory extends ViewRecord
{
    protected static string $resource = StoryResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('title')
                    ->size(TextEntrySize::Large)
                    ->weight(FontWeight::Black),
                TextEntry::make('user.name'),
                TextEntry::make('category.name'),
                TextEntry::make('tags')
                    ->badge()
                    ->separator(','),
                ImageEntry::make('cover'),
                RepeatableEntry::make('episodes')
                    ->schema([
                        TextEntry::make('title')
                            ->label('')
                            ->alignCenter()
                            ->weight(FontWeight::Black)
                            ->size(TextEntrySize::Medium)
                            ->url(fn (Episode $episode): string => EpisodeResource::getUrl('view', ['record' => $episode->id]))
                    ])
                    ->columnSpanFull()
            ]);
    }
}
