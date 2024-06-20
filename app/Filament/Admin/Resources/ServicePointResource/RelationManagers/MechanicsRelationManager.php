<?php

namespace App\Filament\Admin\Resources\ServicePointResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Enums\UserRoles;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class MechanicsRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('filament.users.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label(__('filament.users.phone'))
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label(__('filament.users.email'))
                    ->email()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            // Modify base query, we only want to see mechanics here
            ->modifyQueryUsing(function (Builder $query) { 
                return $query->where('role_id', UserRoles::Mechanic); 
            }) 
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label(__('filament.users.name')),
                Tables\Columns\TextColumn::make('phone')
                ->label(__('filament.users.phone'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('email')
                ->label(__('filament.users.email'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('role.name')
                ->label(__('filament.users.role'))
                ->badge()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
