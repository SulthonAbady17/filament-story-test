<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EpisodeResource\Pages;
use App\Models\Episode;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EpisodeResource extends Resource
{
    protected static ?string $model = Episode::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $activeNavigationIcon = 'heroicon-s-document-text';

    protected static ?int $navigationSort = 2;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('story_id')
                            ->relationship('story', 'title', fn (Builder $query) => $query->where('user_id', auth()->id()))
                            ->searchable()
                            ->preload()
                            ->autofocus()
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->autocapitalize()
                            ->autocomplete(false),
                    ])->columns(2),
                Section::make()
                    ->schema([
                        Forms\Components\RichEditor::make('body')
                            ->required()
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock',
                                'link',
                            ]),
                    ])->columnSpanFull(),
                Forms\Components\Checkbox::make('is_published')
                    ->label('Published')
                    ->default(true),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->id()))
            ->recordTitleAttribute('slug')
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->description(fn (Episode $episode): string => $episode->created_at->since())
                    ->searchable()
                    ->words(5),
                Tables\Columns\TextColumn::make('story.title')
                    ->description(fn (Episode $episode): string => $episode->user->name)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Published'),
            ])
            ->filters([
                SelectFilter::make('story')
                    ->relationship('story', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEpisodes::route('/'),
            'create' => Pages\CreateEpisode::route('/create'),
            'edit' => Pages\EditEpisode::route('/{record}/edit'),
            'view' => Pages\ViewEpisode::route('/{record}'),
        ];
    }
}
