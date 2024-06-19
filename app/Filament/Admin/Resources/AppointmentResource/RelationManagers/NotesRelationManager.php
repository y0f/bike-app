<?php

namespace App\Filament\Admin\Resources\AppointmentResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\RelationManagers\RelationManager;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $icon = 'heroicon-o-computer-desktop';

    public function getLabel(): ?string
    {
        return __('notes.label');
    }

    public function getPluralLabel(): ?string
    {
        return __('notes.plural_label');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('notes.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('body')
                    ->label(__('notes.body'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(__('notes.body'))
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('created_at')
                            ->label(__('notes.created_at'))
                            ->date('d-m-Y | h:i')
                            ->color(Color::Orange),
                        Tables\Columns\TextColumn::make('body')
                            ->label(__('notes.body'))
                            ->formatStateUsing(fn ($state) => strip_tags($state))
                            ->weight(FontWeight::SemiBold),
                    ])->space(1),
                ])
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label(__('notes.actions.create')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(__('notes.actions.view')),
                Tables\Actions\DeleteAction::make()->label(__('notes.actions.delete')),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
