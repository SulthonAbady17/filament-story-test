<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryResource\Pages;
use App\Filament\Resources\StoryResource\RelationManagers\EpisodesRelationManager;
use App\Models\Story;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StoryResource extends Resource
{
    protected static ?string $model = Story::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $activeNavigationIcon = 'heroicon-s-book-open';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('slug')
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Split::make([
                    ImageColumn::make('cover')
                        ->height('auto')
                        ->width(100)
                        ->grow(false),
                    Stack::make([
                        TextColumn::make('title')
                            ->description(fn (Story $story): string => $story->updated_at->since())
                            ->words(5)
                            ->searchable()
                            ->visibleFrom('lg'),
                        TextColumn::make('title')
                            ->words(5)
                            ->searchable()
                            ->weight(FontWeight::Bold)
                            ->hiddenFrom('lg'),
                        TextColumn::make('user.name')
                            ->formatStateUsing(fn (string $state): string => "By " . $state)
                            ->size(TextColumnSize::ExtraSmall)
                            ->weight(FontWeight::Bold)
                            ->color('gray')
                            ->label('Author')
                            ->searchable(),
                        TextColumn::make('synopsis')
                            ->markdown()
                            ->words(10),
                        // SpatieTagsColumn::make('tags'),
                        TextColumn::make('updated_at')
                            ->size(TextColumnSize::ExtraSmall)
                            ->since()
                            ->color('gray')
                            ->hiddenFrom('lg'),
                    ])
                        ->alignStart(),
                    // TextColumn::make('category.name')
                    //     ->searchable()
                    //     ->visibleFrom('md'),
                ])
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship(name: 'category', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->native(false),
                SelectFilter::make('user')
                    ->relationship(name: 'user', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->native(false),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // EpisodesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStories::route('/'),
            'view' => Pages\ViewStory::route('/{record}'),
        ];
    }
}
