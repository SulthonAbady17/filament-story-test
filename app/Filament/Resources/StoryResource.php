<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryResource\Pages;
use App\Filament\Resources\StoryResource\RelationManagers;
use App\Filament\Resources\StoryResource\RelationManagers\EpisodesRelationManager;
use App\Models\Story;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoryResource extends Resource
{
    protected static ?string $model = Story::class;

    protected static ?string $navigationIcon = 'heroicon-s-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->autocapitalize()
                            ->autofocus()
                            ->autocomplete(false),
                        Select::make('category_id')
                            ->relationship(name: 'category', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                        SpatieTagsInput::make('tags'),
                        Checkbox::make('is_published')
                            ->label('Publish')
                            ->default(true)
                    ])->columnSpan(2),
                Section::make()
                    ->schema([
                        FileUpload::make('cover')
                            ->image()
                            ->maxSize(1024)
                            ->imageEditor()
                            ->imageCropAspectRatio('9:16')
                            ->directory('covers')
                    ])->columnSpan(1),
                Section::make()
                    ->schema([
                        RichEditor::make('synopsis')
                            ->required()
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock',
                                'link'
                            ]),
                    ])->columnSpanFull(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('slug')
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->description(fn (Story $story): string => $story->created_at->since())
                    ->words(5)
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
                SpatieTagsColumn::make('tags'),
                ToggleColumn::make('is_published')
                    ->label('Published')
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship(name: 'category', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->native(false),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                DeleteAction::make()
                // Tables\Actions\EditAction::make()
                    // ->url(fn (Story $story): string => static::getUrl('edit', ['record' => $story->slug])),
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
            EpisodesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStories::route('/'),
            'create' => Pages\CreateStory::route('/create'),
            // 'view' => Pages\ViewStory::route('/{record}'),
            'edit' => Pages\EditStory::route('/{record}/edit'),
        ];
    }
}
