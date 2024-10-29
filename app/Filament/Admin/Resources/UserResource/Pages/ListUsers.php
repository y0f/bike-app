<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use Filament\Actions;
use App\Filament\Admin\Imports\UserImporter;
use App\Filament\Admin\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('filament.users.create'))
                ->icon('heroicon-o-user'),
            Actions\ImportAction::make()
                ->label(__('filament.users.import'))
                ->color('primary')
                ->icon('heroicon-o-user-group')
                ->importer(UserImporter::class),
        ];
    }
}
