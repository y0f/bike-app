<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Filament\Imports\UserImporter;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
             ->label('Gebruikers importeren')
             ->color('primary')
             ->icon('heroicon-o-user-group')
             ->importer(UserImporter::class),
            Actions\CreateAction::make()
             ->label('Nieuwe gebruiker aanmaken')
             ->icon('heroicon-o-user'),
        ];
    }
}
