<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryResource\Pages;
use App\Filament\Resources\StoryResource\RelationManagers;
use App\Models\Story;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                            ->autofocus(),
                        Select::make('category_id')
                            ->relationship(name: 'category', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
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
                            ->minLength(10)
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
            ->columns([
                TextColumn::make('title')
                    ->description(fn (Story $story): string => $story->created_at->since()),
                TextColumn::make('category.name'),
                ImageColumn::make('cover')
                    ->extraImgAttributes(['loading' => 'lazy'])
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListStories::route('/'),
            'create' => Pages\CreateStory::route('/create'),
            'view' => Pages\ViewStory::route('/{record}'),
            'edit' => Pages\EditStory::route('/{record}/edit'),
        ];
    }
}
