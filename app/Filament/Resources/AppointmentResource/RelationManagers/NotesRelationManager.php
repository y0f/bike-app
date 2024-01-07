<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $label = 'notitie';

    protected static ?string $pluralLabel = 'notitie';

    protected static ?string $title = 'Notities';

    protected static ?string $icon = 'heroicon-o-computer-desktop';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('body')
                    ->label('')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('')
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('created_at')
                            ->date('d-m-Y | h:i')
                            ->color(Color::Orange),
                        Tables\Columns\TextColumn::make('body')
                            ->formatStateUsing(fn ($state) => strip_tags($state))
                            // ->bulleted()
                            ->weight(FontWeight::SemiBold),
                    ])->space(1),
                ])
            ])
            ->filters([
                //
            ])
            ->headerActions([
                 Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                 Tables\Actions\ViewAction::make(),
                 Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
