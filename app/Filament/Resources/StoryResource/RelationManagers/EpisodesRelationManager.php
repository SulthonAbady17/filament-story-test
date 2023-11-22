<?php

namespace App\Filament\Resources\StoryResource\RelationManagers;

use App\Models\Episode;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EpisodesRelationManager extends RelationManager
{
    protected static string $relationship = 'episodes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->autocapitalize()
                    ->autofocus()
                    ->autocomplete(false)
                    ->columnSpanFull(),
                RichEditor::make('body')
                    ->required()
                    ->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                        'link'
                    ])
                    ->columnSpanFull(),
                Checkbox::make('is_published')
                    ->label('Publish')
                    ->default(true)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('created_at', 'asc')
            ->columns([
                TextColumn::make('title')
                    ->description(fn (Episode $episode): string => $episode->created_at->since())
                    ->words(5)
                    ->searchable(),
                ToggleColumn::make('is_published')
                    ->label('Published')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                // Action::make('create')
                //     ->action(fn () => route('episodes.create'))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
