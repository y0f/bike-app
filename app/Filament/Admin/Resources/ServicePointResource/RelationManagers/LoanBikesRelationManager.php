<?php

namespace App\Filament\Admin\Resources\ServicePointResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\LoanBikeResource;
use Filament\Resources\RelationManagers\RelationManager;

class LoanBikesRelationManager extends RelationManager
{
    protected static string $relationship = 'loanBikes';

    public function form(Form $form): Form
    {
        return LoanBikeResource::form($form);
    }

    public function table(Table $table): Table
    {
        return LoanBikeResource::table($table);
    }
}
