<?php

namespace App\Filament\Resources\LoanBikeResource\Pages;

use App\Filament\Resources\LoanBikeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListLoanBikes extends ListRecords
{
    protected static string $resource = LoanBikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Refacting UI @ work example

    // public function getTabs(): array
    // {
    //     return [
    //         "Alle middelen" => Tab::make(),
    //         "Auto's" => Tab::make()
    //         ->icon('icon-cars')
    //         ->badgeColor('success'),
    //         "Huurauto's" => Tab::make()
    //         ->icon('icon-rent-cars'),
    //         "Poolauto's" => Tab::make()
    //         ->icon('icon-pool-cars'),
    //         "Fietsen" => Tab::make()
    //         ->icon('icon-bike'),
    //     ];
    // }
}
