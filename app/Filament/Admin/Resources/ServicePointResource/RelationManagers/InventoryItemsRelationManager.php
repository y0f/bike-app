<?php

namespace App\Filament\Admin\Resources\ServicePointResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\InventoryItemResource;
use Filament\Resources\RelationManagers\RelationManager;

class InventoryItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'inventoryItems';

    public function form(Form $form): Form
    {
        return InventoryItemResource::form($form);
    }

    public function table(Table $table): Table
    {
        return InventoryItemResource::table($table);
    }
}
