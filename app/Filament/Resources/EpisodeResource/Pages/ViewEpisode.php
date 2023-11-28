<?php

namespace App\Filament\Resources\EpisodeResource\Pages;

use App\Filament\Resources\EpisodeResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewEpisode extends ViewRecord
{
    protected static string $resource = EpisodeResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('title')
                    ->label('')
                    ->size(TextEntrySize::Large)
                    ->weight(FontWeight::Bold)
                    ->columnSpanFull()
                    ->alignCenter(),
                TextEntry::make('body')
                    ->label('')
                    ->markdown()
                    ->columnSpanFull()
                    ->alignCenter(),
            ]);
    }
}
