<?php

namespace App\Filament\Resources\StoryResource\Pages;

use App\Filament\Resources\EpisodeResource;
use App\Filament\Resources\StoryResource;
use App\Models\Episode;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
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
            ->columns(1)
            ->schema([
                Split::make([
                    Fieldset::make('information')
                        ->schema([
                            TextEntry::make('created_at')
                                ->date(),
                            TextEntry::make('user.name')
                                ->label('Author'),
                            TextEntry::make('category.name'),

                        ]),
                    // Section::make('Information')
                    //     ->schema([
                    //         TextEntry::make('created_at')
                    //             ->date(),
                    //         TextEntry::make('user.name')
                    //             ->label('Author'),
                    //         TextEntry::make('category.name'),
                    //     ])->collapsed(),
                    Section::make()
                        ->schema([
                            TextEntry::make('title')
                                ->label('')
                                ->alignCenter()
                                ->size(TextEntrySize::Large)
                                ->weight(FontWeight::Black),
                            // ImageEntry::make('cover')
                            //     ->label('')
                            //     ->height('auto')
                            //     ->width(200)
                            //     ->alignCenter(),
                            TextEntry::make('synopsis')
                                ->label('')
                                ->markdown()
                                ->prose(),
                        ])->grow(),
                ])->from('md'),
                RepeatableEntry::make('episodes')
                    ->schema([
                        TextEntry::make('title')
                            ->label('')
                            ->alignStart()
                            ->weight(FontWeight::SemiBold)
                            ->size(TextEntrySize::Small)
                            ->url(fn (Episode $episode): string => EpisodeResource::getUrl('view', ['record' => $episode->id])),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
